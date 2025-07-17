<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::with(['project', 'task']);

        // Apply filters
        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('task')) {
            $query->where('task_id', $request->task);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('activity_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('activity_date', '<=', $request->date_to);
        }

        $activities = $query->orderBy('activity_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $projects = Project::all();
        $tasks = Task::with('project')->get();

        return view('activities.index', compact('activities', 'projects', 'tasks'));
    }

    public function create(): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $tasks = Task::whereIn('status', ['pending', 'in_progress'])->get();
        
        return view('activities.create', compact('projects', 'tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Validate that end_time is after start_time if both are provided
        if ($validated['start_time'] && $validated['end_time']) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
            $end = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
            
            if ($end->lte($start)) {
                return back()->withErrors(['end_time' => 'The end time must be after the start time.'])->withInput();
            }
            
            // Calculate duration
            $validated['duration_minutes'] = $start->diffInMinutes($end);
        }

        $activity = Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activity logged successfully!');
    }

    public function show(Activity $activity): View
    {
        $activity->load(['project', 'task']);
        
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $tasks = Task::whereIn('status', ['pending', 'in_progress'])->get();
        
        return view('activities.edit', compact('activity', 'projects', 'tasks'));
    }

    public function update(Request $request, Activity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Validate that end_time is after start_time if both are provided
        if ($validated['start_time'] && $validated['end_time']) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $validated['start_time']);
            $end = \Carbon\Carbon::createFromFormat('H:i', $validated['end_time']);
            
            if ($end->lte($start)) {
                return back()->withErrors(['end_time' => 'The end time must be after the start time.'])->withInput();
            }
            
            // Calculate duration
            $validated['duration_minutes'] = $start->diffInMinutes($end);
        }

        $activity->update($validated);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully!');
    }
}
