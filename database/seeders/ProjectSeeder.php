<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use App\Models\TomorrowPlan;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample projects
        $project1 = Project::create([
            'name' => 'DevTime Laravel Project',
            'description' => 'Project management system built with Laravel',
            'start_date' => Carbon::now()->subDays(10),
            'deadline' => Carbon::now()->addDays(20),
            'status' => 'in_progress'
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App Development',
            'description' => 'React Native mobile application',
            'start_date' => Carbon::now()->subDays(5),
            'deadline' => Carbon::now()->addDays(30),
            'status' => 'planning'
        ]);

        // Create sample tasks
        $task1 = Task::create([
            'project_id' => $project1->id,
            'title' => 'Database Schema Design',
            'description' => 'Design and implement database schema for project management',
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => Carbon::now()->subDays(5)
        ]);

        $task2 = Task::create([
            'project_id' => $project1->id,
            'title' => 'User Authentication',
            'description' => 'Implement user registration and login functionality',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => Carbon::now()->addDays(3)
        ]);

        $task3 = Task::create([
            'project_id' => $project2->id,
            'title' => 'UI/UX Design',
            'description' => 'Create wireframes and design mockups',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => Carbon::now()->addDays(7)
        ]);

        // Create sample activities
        Activity::create([
            'project_id' => $project1->id,
            'task_id' => $task1->id,
            'description' => 'Created database migrations for all tables',
            'activity_date' => Carbon::today(),
            'start_time' => '09:00',
            'end_time' => '11:30',
            'duration_minutes' => 150,
            'notes' => 'Successfully created all required database tables with proper relationships'
        ]);

        Activity::create([
            'project_id' => $project1->id,
            'task_id' => $task2->id,
            'description' => 'Working on authentication system',
            'activity_date' => Carbon::today(),
            'start_time' => '13:00',
            'end_time' => '16:00',
            'duration_minutes' => 180,
            'notes' => 'Implemented user registration, need to work on login validation'
        ]);

        // Create sample tomorrow plans
        TomorrowPlan::create([
            'project_id' => $project1->id,
            'task_id' => $task2->id,
            'title' => 'Complete user authentication',
            'description' => 'Finish login validation and password reset functionality',
            'planned_date' => Carbon::tomorrow(),
            'start_time' => '09:00',
            'estimated_duration' => 240,
            'priority' => 'high'
        ]);

        TomorrowPlan::create([
            'project_id' => $project2->id,
            'task_id' => $task3->id,
            'title' => 'Start UI design mockups',
            'description' => 'Begin creating wireframes for main app screens',
            'planned_date' => Carbon::tomorrow(),
            'start_time' => '14:00',
            'estimated_duration' => 180,
            'priority' => 'medium'
        ]);
    }
}
