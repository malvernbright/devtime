@extends('layouts.app')

@section('title', $project->name . ' - DevTime')
@section('page-title', $project->name)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Project
        </a>
        <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Task
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Project Details -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Project Details</h5>
            </div>
            <div class="card-body">
                @if($project->description)
                    <p class="text-muted">{{ $project->description }}</p>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6>Project Information</h6>
                        <ul class="list-unstyled">
                            <li><strong>Status:</strong> 
                                <span class="badge status-{{ $project->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </li>
                            <li><strong>Priority:</strong> 
                                <span class="badge priority-{{ $project->priority }}">
                                    {{ ucfirst($project->priority) }}
                                </span>
                            </li>
                            <li><strong>Start Date:</strong> {{ $project->start_date->format('M d, Y') }}</li>
                            <li><strong>Deadline:</strong> 
                                <span class="{{ $project->isOverdue() ? 'text-danger' : '' }}">
                                    {{ $project->deadline->format('M d, Y') }}
                                    ({{ $project->daysUntilDeadline() }} days {{ $project->daysUntilDeadline() >= 0 ? 'remaining' : 'overdue' }})
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Progress</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Overall Progress</span>
                                <span>{{ $project->progress }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $project->progress }}%" 
                                     aria-valuenow="{{ $project->progress }}" 
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Tasks Completed</span>
                                <span>{{ $project->completedTasksCount() }}/{{ $project->totalTasksCount() }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $project->totalTasksCount() > 0 ? ($project->completedTasksCount() / $project->totalTasksCount()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($project->notes)
                    <div class="mt-4">
                        <h6>Notes</h6>
                        <div class="alert alert-light">
                            {{ $project->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tasks -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tasks</h5>
                <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Task
                </a>
            </div>
            <div class="card-body">
                @if($project->tasks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($project->tasks as $task)
                            <div class="list-group-item d-flex justify-content-between align-items-start border-0 px-0">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                            {{ $task->title }}
                                        </a>
                                    </h6>
                                    @if($task->description)
                                        <p class="mb-1 text-muted">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    <small class="text-muted">
                                        @if($task->due_date)
                                            Due: {{ $task->due_date->format('M d, Y') }}
                                        @endif
                                        @if($task->estimated_hours)
                                            | Estimated: {{ $task->estimated_hours }}h
                                        @endif
                                    </small>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge status-{{ $task->status }} mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    <span class="badge priority-{{ $task->priority }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">
                        No tasks yet. <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}">Create the first task</a>!
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Recent Activities -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Recent Activities</h6>
            </div>
            <div class="card-body">
                @if($project->activities->count() > 0)
                    <div class="timeline">
                        @foreach($project->activities->take(5) as $activity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 30px; height: 30px; font-size: 12px;">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1 fs-6">{{ $activity->title }}</h6>
                                    <small class="text-muted">
                                        {{ $activity->activity_date->format('M d') }}
                                        @if($activity->duration_minutes)
                                            | @durationShort($activity->duration_minutes)
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="{{ route('activities.index') }}?project={{ $project->id }}" class="btn btn-sm btn-outline-primary">
                            View All Activities
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center">No activities logged yet.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Add Task
                    </a>
                    <a href="{{ route('activities.create') }}?project_id={{ $project->id }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-clock me-2"></i>Log Activity
                    </a>
                    <a href="{{ route('tomorrow-plans.create') }}?project_id={{ $project->id }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-calendar-day me-2"></i>Plan Tomorrow
                    </a>
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Project
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
