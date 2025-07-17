@extends('layouts.app')

@section('title', 'Projects - DevTime')
@section('page-title', 'Projects')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Project
        </a>
        <a href="{{ route('reports.status') }}?date={{ date('Y-m-d') }}&location=ZAMBIA OPERATIONS" class="btn btn-outline-success">
            <i class="fas fa-file-word me-2"></i>Export Project Status
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    @if($projects->count() > 0)
        @foreach($projects as $project)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">{{ $project->name }}</h6>
                        <span class="badge status-{{ $project->status }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($project->description)
                            <p class="text-muted">{{ Str::limit($project->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-3">
                            <small class="text-muted">Progress</small>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $project->progress }}%" 
                                     aria-valuenow="{{ $project->progress }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ $project->progress }}%
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col">
                                <small class="text-muted">Start Date</small>
                                <div class="fw-bold">{{ $project->start_date->format('M d, Y') }}</div>
                            </div>
                            <div class="col">
                                <small class="text-muted">Deadline</small>
                                <div class="fw-bold {{ $project->isOverdue() ? 'text-danger' : '' }}">
                                    {{ $project->deadline->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">Priority</small>
                            <span class="badge priority-{{ $project->priority }} ms-2">
                                {{ ucfirst($project->priority) }}
                            </span>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">Tasks: {{ $project->completedTasksCount() }}/{{ $project->totalTasksCount() }} completed</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <div>
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('projects.destroy', $project) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this project?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No projects yet</h4>
                <p class="text-muted">Create your first project to get started!</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Project
                </a>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-12">
        {{ $projects->links() }}
    </div>
</div>
@endsection
