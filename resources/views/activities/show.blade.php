@extends('layouts.app')

@section('title', 'Activity Details - DevTime')
@section('page-title', 'Activity Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Activity Information</h5>
                <div class="btn-group" role="group">
                    <a href="{{ route('activities.edit', $activity) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('activities.destroy', $activity) }}" 
                          class="d-inline" onsubmit="return confirm('Are you sure you want to delete this activity?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $activity->description }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Date:</strong>
                    </div>
                    <div class="col-sm-9">
                        <i class="fas fa-calendar me-2 text-primary"></i>
                        {{ $activity->activity_date->format('l, F j, Y') }}
                    </div>
                </div>

                @if($activity->project)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Project:</strong>
                        </div>
                        <div class="col-sm-9">
                            <a href="{{ route('projects.show', $activity->project) }}" class="text-decoration-none">
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-project-diagram me-1"></i>{{ $activity->project->name }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endif

                @if($activity->task)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Task:</strong>
                        </div>
                        <div class="col-sm-9">
                            <a href="{{ route('tasks.show', $activity->task) }}" class="text-decoration-none">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-tasks me-1"></i>{{ $activity->task->title }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Duration:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="h5 text-success">
                            <i class="fas fa-clock me-2"></i>@duration($activity->duration_minutes)
                        </span>
                        <small class="text-muted ms-2">({{ $activity->duration_minutes }} minutes)</small>
                    </div>
                </div>

                @if($activity->start_time || $activity->end_time)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Time Range:</strong>
                        </div>
                        <div class="col-sm-9">
                            @if($activity->start_time)
                                <i class="fas fa-play text-success me-1"></i>
                                <strong>Start:</strong> {{ $activity->start_time }}
                            @endif
                            @if($activity->start_time && $activity->end_time)
                                <span class="mx-2">→</span>
                            @endif
                            @if($activity->end_time)
                                <i class="fas fa-stop text-danger me-1"></i>
                                <strong>End:</strong> {{ $activity->end_time }}
                            @endif
                        </div>
                    </div>
                @endif

                @if($activity->notes)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Notes:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                {{ $activity->notes }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-3">
                        <strong>Logged:</strong>
                    </div>
                    <div class="col-sm-9">
                        <small class="text-muted">
                            {{ $activity->created_at->format('M j, Y \a\t g:i A') }}
                            @if($activity->created_at != $activity->updated_at)
                                <span class="ms-2">(Updated: {{ $activity->updated_at->format('M j, Y \a\t g:i A') }})</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('activities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Log New Activity
                    </a>
                    
                    @if($activity->project)
                        <a href="{{ route('projects.show', $activity->project) }}" class="btn btn-outline-primary">
                            <i class="fas fa-project-diagram me-2"></i>View Project
                        </a>
                    @endif
                    
                    @if($activity->task)
                        <a href="{{ route('tasks.show', $activity->task) }}" class="btn btn-outline-info">
                            <i class="fas fa-tasks me-2"></i>View Task
                        </a>
                    @endif
                    
                    <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>All Activities
                    </a>
                </div>
            </div>
        </div>

        @if($activity->project)
            <!-- Project Progress -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Project Progress</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small">{{ $activity->project->name }}</span>
                            <span class="small text-muted">{{ number_format($activity->project->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $activity->project->progress_percentage }}%"
                                 aria-valuenow="{{ $activity->project->progress_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="small text-muted">Completed Tasks</div>
                            <div class="fw-bold">{{ $activity->project->completedTasksCount() }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Total Tasks</div>
                            <div class="fw-bold">{{ $activity->project->tasks->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($activity->task)
            <!-- Task Status -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Task Status</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-{{ $activity->task->status == 'completed' ? 'success' : ($activity->task->status == 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $activity->task->status)) }}
                        </span>
                    </div>
                    
                    @if($activity->task->priority)
                        <div class="mb-3">
                            <strong>Priority:</strong>
                            <span class="badge bg-{{ $activity->task->priority == 'urgent' ? 'danger' : ($activity->task->priority == 'high' ? 'warning' : ($activity->task->priority == 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($activity->task->priority) }}
                            </span>
                        </div>
                    @endif
                    
                    @if($activity->task->due_date)
                        <div class="mb-3">
                            <strong>Due Date:</strong><br>
                            <span class="{{ $activity->task->isOverdue() ? 'text-danger' : 'text-muted' }}">
                                {{ $activity->task->due_date->format('M j, Y') }}
                                @if($activity->task->isOverdue())
                                    <i class="fas fa-exclamation-triangle ms-1"></i>
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    <div>
                        <strong>Total Time Spent:</strong><br>
                        <span class="text-success">@duration($activity->task->totalTimeSpent())</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
