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
        $user = auth()->user();
        $query = $user->tasks()->with(['project']);

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
        $projects = $user->projects()->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create(Request $request): View
    {
        $user = auth()->user();
        $projects = $user->projects()
                         ->whereIn('status', ['planning', 'in_progress', 'on_hold'])
                         ->orderBy('name')
                         ->get();
        
        // Get the preselected project ID from query parameter
        $selectedProjectId = $request->query('project_id');
        
        return view('tasks.create', compact('projects', 'selectedProjectId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
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

        // Ensure the project belongs to the authenticated user
        $project = $user->projects()->findOrFail($validated['project_id']);
        
        $validated['user_id'] = $user->id;
        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task): View
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $task->load([
            'project',
            'activities' => function ($query) {
                $query->where('user_id', auth()->id());
            }
        ]);
        
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $projects = auth()->user()->projects()->where('status', '!=', 'completed')->get();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $user = auth()->user();
        
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

        // Ensure the project belongs to the authenticated user
        $project = $user->projects()->findOrFail($validated['project_id']);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task): RedirectResponse
    {
        // Ensure the task belongs to the authenticated user
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}
