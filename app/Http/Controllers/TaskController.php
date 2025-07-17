<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with(['project']);

        // Apply filters
        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create(): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        return view('tasks.create', compact('projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0.5',
            'notes' => 'nullable|string',
        ]);

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task): View
    {
        $task->load(['project', 'activities']);
        
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0.5',
            'notes' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}
