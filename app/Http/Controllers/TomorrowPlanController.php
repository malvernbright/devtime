<?php

namespace App\Http\Controllers;

use App\Models\TomorrowPlan;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TomorrowPlanController extends Controller
{
    public function index(): View
    {
        $plans = TomorrowPlan::with(['project', 'task'])
            ->where('planned_date', '>=', now())
            ->orderBy('planned_date')
            ->orderBy('start_time')
            ->paginate(15);

        return view('tomorrow-plans.index', compact('plans'));
    }

    public function create(): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $tasks = Task::whereIn('status', ['todo', 'in_progress'])->get();
        
        return view('tomorrow-plans.create', compact('projects', 'tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'estimated_duration' => 'nullable|integer|min:1',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $plan = TomorrowPlan::create($validated);

        return redirect()->route('tomorrow-plans.index')
            ->with('success', 'Tomorrow plan created successfully!');
    }

    public function show(TomorrowPlan $tomorrowPlan): View
    {
        $tomorrowPlan->load(['project', 'task']);
        
        return view('tomorrow-plans.show', compact('tomorrowPlan'));
    }

    public function edit(TomorrowPlan $tomorrowPlan): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $tasks = Task::whereIn('status', ['todo', 'in_progress'])->get();
        
        return view('tomorrow-plans.edit', compact('tomorrowPlan', 'projects', 'tasks'));
    }

    public function update(Request $request, TomorrowPlan $tomorrowPlan): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'estimated_duration' => 'nullable|integer|min:1',
            'priority' => 'required|in:low,medium,high,urgent',
            'is_completed' => 'boolean',
        ]);

        $tomorrowPlan->update($validated);

        return redirect()->route('tomorrow-plans.show', $tomorrowPlan)
            ->with('success', 'Tomorrow plan updated successfully!');
    }

    public function destroy(TomorrowPlan $tomorrowPlan): RedirectResponse
    {
        $tomorrowPlan->delete();

        return redirect()->route('tomorrow-plans.index')
            ->with('success', 'Tomorrow plan deleted successfully!');
    }

    public function markCompleted(TomorrowPlan $tomorrowPlan): RedirectResponse
    {
        $tomorrowPlan->update(['is_completed' => true]);

        return redirect()->back()
            ->with('success', 'Plan marked as completed!');
    }
}
