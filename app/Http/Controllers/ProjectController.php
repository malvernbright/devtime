<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = auth()->user()->projects()
            ->with('tasks')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project): View
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $project->load([
            'tasks' => function ($query) {
                $query->where('user_id', auth()->id());
            },
            'activities' => function ($query) {
                $query->where('user_id', auth()->id());
            },
            'notifications'
        ]);
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project): RedirectResponse
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
