<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use App\Models\TomorrowPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Export daily operations report
     */
    public function exportDailyReport(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $consultant = $request->get('consultant', 'Malvern');
        
        // Get activities for the specified date
        $activities = Activity::with(['project', 'task'])
            ->whereDate('activity_date', $date)
            ->get();
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Title
        $section->addText('Daily Operations Report - ' . Carbon::parse($date)->format('d/m/Y'), 
            ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);
        
        // Create table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50
        ]);
        
        // Header row
        $table->addRow(700);
        $table->addCell(2000)->addText('Consultant', ['bold' => true]);
        $table->addCell(2500)->addText('Project/Task', ['bold' => true]);
        $table->addCell(2000)->addText('Planned Time', ['bold' => true]);
        $table->addCell(4000)->addText('Task Done', ['bold' => true]);
        $table->addCell(1500)->addText('Time Spent', ['bold' => true]);
        $table->addCell(1500)->addText('Status', ['bold' => true]);
        $table->addCell(1500)->addText('Deadline', ['bold' => true]);
        
        // Group activities by project
        $groupedActivities = $activities->groupBy('project_id');
        
        foreach ($groupedActivities as $projectId => $projectActivities) {
            $project = $projectActivities->first()->project;
            $totalTime = $projectActivities->sum('duration_minutes');
            
            // Main project row
            $table->addRow();
            $table->addCell(2000)->addText($consultant);
            $table->addCell(2500)->addText($project ? $project->name : 'No Project');
            $table->addCell(2000)->addText(''); // Planned time
            
            // Combine all descriptions for this project
            $tasksDone = [];
            $tasksDone[] = "Today's Achievements:";
            foreach ($projectActivities as $activity) {
                $tasksDone[] = "- " . $activity->description;
                if ($activity->task) {
                    $tasksDone[] = "  (Task: " . $activity->task->title . ")";
                }
            }
            $tasksDone[] = "\nTask Done";
            
            $table->addCell(4000)->addText(implode("\n", $tasksDone));
            $table->addCell(1500)->addText(number_format($totalTime / 60, 1) . ' hours');
            $table->addCell(1500)->addText('Done');
            $table->addCell(1500)->addText($project ? $project->deadline->format('d/m/Y') : '');
        }
        
        $fileName = 'Daily_Operations_Report_' . str_replace('-', '_', $date) . '.docx';
        
        return $this->downloadDocument($phpWord, $fileName);
    }
    
    /**
     * Export planned tasks for tomorrow
     */
    public function exportPlannedTasks(Request $request)
    {
        $date = $request->get('date', now()->addDay()->toDateString());
        $consultant = $request->get('consultant', 'Kelvin');
        
        // Get tomorrow plans for the specified date
        $plans = TomorrowPlan::with(['project', 'task'])
            ->whereDate('planned_date', $date)
            ->get();
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Title
        $section->addText('Planned Task For Tomorrow ' . Carbon::parse($date)->format('d/m/Y'), 
            ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);
        
        // Create table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50
        ]);
        
        // Header row
        $table->addRow(700);
        $table->addCell(2000)->addText('Consultant', ['bold' => true]);
        $table->addCell(3000)->addText('Task/Project', ['bold' => true]);
        $table->addCell(6000)->addText('Task To Be Done', ['bold' => true]);
        $table->addCell(2000)->addText('Planned Hours', ['bold' => true]);
        
        // Group plans by consultant (for now, using single consultant)
        foreach ($plans as $plan) {
            $table->addRow();
            $table->addCell(2000)->addText($consultant);
            
            $projectTask = [];
            if ($plan->project) {
                $projectTask[] = $plan->project->name;
            }
            if ($plan->task) {
                $projectTask[] = $plan->task->title;
            }
            
            $table->addCell(3000)->addText(implode(' - ', $projectTask));
            $table->addCell(6000)->addText($plan->description);
            $table->addCell(2000)->addText(number_format($plan->estimated_duration / 60, 0) . 'hrs');
        }
        
        $fileName = 'Planned_Tasks_' . str_replace('-', '_', $date) . '.docx';
        
        return $this->downloadDocument($phpWord, $fileName);
    }
    
    /**
     * Export project status report
     */
    public function exportProjectStatus(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $location = $request->get('location', 'ZAMBIA OPERATIONS');
        
        // Get all active projects with their tasks and recent activities
        $projects = Project::with(['tasks', 'activities' => function($query) use ($date) {
            $query->whereDate('activity_date', $date);
        }])->where('status', '!=', 'completed')->get();
        
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Title
        $section->addText($location . ' ' . Carbon::parse($date)->format('d/m/Y'), 
            ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);
        
        // Create table
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 100 * 50
        ]);
        
        // Header row
        $table->addRow(700);
        $table->addCell(1500)->addText('Project', ['bold' => true]);
        $table->addCell(2500)->addText('Current(Pending Issues)', ['bold' => true]);
        $table->addCell(1500)->addText('Deadline', ['bold' => true]);
        $table->addCell(2500)->addText('Task Done Today', ['bold' => true]);
        $table->addCell(1500)->addText('Customer Complaints', ['bold' => true]);
        $table->addCell(1500)->addText('Solution', ['bold' => true]);
        $table->addCell(2500)->addText('Next Course of Action', ['bold' => true]);
        
        foreach ($projects as $project) {
            $table->addRow();
            $table->addCell(1500)->addText($project->name);
            
            // Current/Pending Issues (incomplete tasks)
            $pendingTasks = $project->tasks->where('status', '!=', 'completed');
            $pendingIssues = [];
            foreach ($pendingTasks as $task) {
                $pendingIssues[] = "- " . $task->title;
            }
            $table->addCell(2500)->addText(implode("\n", $pendingIssues));
            
            $table->addCell(1500)->addText($project->deadline->format('d/m/Y'));
            
            // Tasks done today
            $todayTasks = [];
            foreach ($project->activities as $activity) {
                $todayTasks[] = "- " . $activity->description;
            }
            $table->addCell(2500)->addText(implode("\n", $todayTasks));
            
            $table->addCell(1500)->addText(''); // Customer complaints
            $table->addCell(1500)->addText(''); // Solution
            
            // Next course of action (upcoming tasks)
            $upcomingTasks = $project->tasks
                ->where('status', 'pending')
                ->where('due_date', '>', now())
                ->sortBy('due_date')
                ->take(3);
            
            $nextActions = [];
            foreach ($upcomingTasks as $task) {
                $nextActions[] = "- " . $task->title;
            }
            $table->addCell(2500)->addText(implode("\n", $nextActions));
        }
        
        $fileName = 'Project_Status_Report_' . str_replace('-', '_', $date) . '.docx';
        
        return $this->downloadDocument($phpWord, $fileName);
    }
    
    /**
     * Helper method to download document
     */
    private function downloadDocument(PhpWord $phpWord, string $fileName)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}
