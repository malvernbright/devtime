@extends('layouts.app')

@section('title', $task->title . ' - DevTime')
@section('page-title', 'Task Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $task->title }}</h5>
                <div class="btn-group" role="group">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" 
                          class="d-inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($task->description)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                {{ $task->description }}
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Project:</strong>
                    </div>
                    <div class="col-sm-9">
                        <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-project-diagram me-1"></i>{{ $task->project->name }}
                            </span>
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                </div>

                @if($task->priority)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Priority:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $task->priority == 'urgent' ? 'danger' : ($task->priority == 'high' ? 'warning' : ($task->priority == 'medium' ? 'info' : 'secondary')) }} fs-6">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>
                @endif

                @if($task->due_date)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Due Date:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="fas fa-calendar me-2 {{ $task->isOverdue() ? 'text-danger' : 'text-primary' }}"></i>
                            <span class="{{ $task->isOverdue() ? 'text-danger' : '' }}">
                                {{ $task->due_date->format('l, F j, Y') }}
                                @if($task->isOverdue())
                                    <span class="badge bg-danger ms-2">OVERDUE</span>
                                @elseif($task->daysUntilDue() <= 3)
                                    <span class="badge bg-warning ms-2">Due Soon</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

                @if($task->estimated_hours)
                    <div class="row mb-4">
                        <div class="col-sm-3">
                            <strong>Estimated Time:</strong>
                        </div>
                        <div class="col-sm-9">
                            <i class="fas fa-clock me-2 text-muted"></i>
                            {{ $task->estimated_hours }} hours
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-sm-3">
                        <strong>Time Spent:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="h5 text-success">
                            <i class="fas fa-stopwatch me-2"></i>{{ number_format($task->totalTimeSpent() / 60, 1) }} hours
                        </span>
                        @if($task->estimated_hours)
                            <div class="progress mt-2" style="height: 8px;">
                                @php
                                    $percentage = min(100, ($task->totalTimeSpent() / 60) / $task->estimated_hours * 100);
                                @endphp
                                <div class="progress-bar {{ $percentage > 100 ? 'bg-danger' : 'bg-success' }}" 
                                     role="progressbar" style="width: {{ $percentage }}%"
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ number_format($percentage, 1) }}% of estimated time
                                @if($percentage > 100)
                                    <span class="text-danger">(Over budget)</span>
                                @endif
                            </small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <strong>Created:</strong>
                    </div>
                    <div class="col-sm-9">
                        <small class="text-muted">
                            {{ $task->created_at->format('M j, Y \a\t g:i A') }}
                            @if($task->created_at != $task->updated_at)
                                <span class="ms-2">(Updated: {{ $task->updated_at->format('M j, Y \a\t g:i A') }})</span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities for this task -->
        @if($task->activities->count() > 0)
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Activities</h6>
                    <a href="{{ route('activities.create') }}?task={{ $task->id }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Log Activity
                    </a>
                </div>
                <div class="card-body">
                    @foreach($task->activities->take(5) as $activity)
                        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="me-3">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">{{ $activity->description }}</div>
                                <div class="text-muted small">
                                    {{ $activity->activity_date->format('M j, Y') }} • 
                                    {{ number_format($activity->duration_minutes / 60, 1) }}h
                                    @if($activity->start_time && $activity->end_time)
                                        • {{ $activity->start_time->format('H:i') }} - {{ $activity->end_time->format('H:i') }}
                                    @endif
                                </div>
                                @if($activity->notes)
                                    <div class="text-muted small mt-1">{{ Str::limit($activity->notes, 100) }}</div>
                                @endif
                            </div>
                            <a href="{{ route('activities.show', $activity) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    @endforeach
                    
                    @if($task->activities->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('activities.index') }}?task={{ $task->id }}" class="btn btn-sm btn-outline-primary">
                                View All Activities ({{ $task->activities->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('activities.create') }}?task={{ $task->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Log Activity
                    </a>
                    
                    @if($task->status !== 'completed')
                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            <input type="hidden" name="estimated_hours" value="{{ $task->estimated_hours }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Mark as Completed
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('tasks.update', $task) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <input type="hidden" name="description" value="{{ $task->description }}">
                            <input type="hidden" name="priority" value="{{ $task->priority }}">
                            <input type="hidden" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            <input type="hidden" name="estimated_hours" value="{{ $task->estimated_hours }}">
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-undo me-2"></i>Mark as In Progress
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('projects.show', $task->project) }}" class="btn btn-outline-primary">
                        <i class="fas fa-project-diagram me-2"></i>View Project
                    </a>
                    
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>All Tasks
                    </a>
                </div>
            </div>
        </div>

        <!-- Project Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Project Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ $task->project->name }}</strong>
                    <div class="small text-muted">{{ $task->project->description }}</div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Progress</span>
                        <span class="small text-muted">{{ number_format($task->project->progress_percentage, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $task->project->progress_percentage }}%"
                             aria-valuenow="{{ $task->project->progress_percentage }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="small text-muted">Deadline</div>
                        <div class="small {{ $task->project->isOverdue() ? 'text-danger' : '' }}">
                            {{ $task->project->deadline->format('M j, Y') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Tasks</div>
                        <div class="small">{{ $task->project->completedTasksCount() }}/{{ $task->project->tasks->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Statistics -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Task Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4 text-primary mb-1">{{ $task->activities->count() }}</div>
                        <div class="text-muted small">Activities</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-success mb-1">{{ number_format($task->totalTimeSpent() / 60, 1) }}h</div>
                        <div class="text-muted small">Time Spent</div>
                    </div>
                    @if($task->activities->count() > 0)
                        <div class="col-6">
                            <div class="h4 text-info mb-1">{{ number_format($task->activities->avg('duration_minutes') / 60, 1) }}h</div>
                            <div class="text-muted small">Avg. Session</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-warning mb-1">{{ $task->activities->max('activity_date')->diffForHumans() }}</div>
                            <div class="text-muted small">Last Activity</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
