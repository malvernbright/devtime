@extends('layouts.devtime')

@section('title', 'Tasks - DevTime')
@section('page-title', 'Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">All Tasks</h4>
        <p class="text-muted mb-0">Manage and track your project tasks</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Task
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="project_filter" class="form-label">Project</label>
                <select class="form-select" id="project_filter" name="project">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status_filter" class="form-label">Status</label>
                <select class="form-select" id="status_filter" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="priority_filter" class="form-label">Priority</label>
                <select class="form-select" id="priority_filter" name="priority">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ request('due_date') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Tasks List -->
@if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">{{ $task->title }}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('tasks.show', $task) }}">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if($task->description)
                            <p class="card-text text-muted small mb-3">{{ Str::limit(strip_tags($task->description), 80) }}</p>
                        @endif

                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">
                                    <i class="fas fa-project-diagram me-1"></i>{{ $task->project->name }}
                                </span>
                                <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                @if($task->priority)
                                    <span class="badge bg-{{ $task->priority == 'urgent' ? 'danger' : ($task->priority == 'high' ? 'warning' : ($task->priority == 'medium' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-auto">
                            @if($task->due_date)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar me-2 {{ $task->isOverdue() ? 'text-danger' : 'text-muted' }}"></i>
                                    <small class="{{ $task->isOverdue() ? 'text-danger' : 'text-muted' }}">
                                        Due: {{ $task->due_date->format('M j, Y') }}
                                        @if($task->isOverdue())
                                            <i class="fas fa-exclamation-triangle ms-1"></i>
                                        @endif
                                    </small>
                                </div>
                            @endif

                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                <small class="text-muted">
                                    {{ number_format($task->totalTimeSpent() / 60, 1) }}h logged
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $tasks->withQueryString()->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Tasks Found</h5>
            <p class="text-muted mb-4">Create your first task to start managing your project work.</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Your First Task
            </a>
        </div>
    </div>
@endif

<!-- Summary Statistics -->
@if($tasks->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Task Statistics</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-2">
                    <div class="h4 text-primary mb-1">{{ $tasks->count() }}</div>
                    <div class="text-muted small">Total Tasks</div>
                </div>
                <div class="col-md-2">
                    <div class="h4 text-success mb-1">{{ $tasks->where('status', 'completed')->count() }}</div>
                    <div class="text-muted small">Completed</div>
                </div>
                <div class="col-md-2">
                    <div class="h4 text-warning mb-1">{{ $tasks->where('status', 'in_progress')->count() }}</div>
                    <div class="text-muted small">In Progress</div>
                </div>
                <div class="col-md-2">
                    <div class="h4 text-secondary mb-1">{{ $tasks->where('status', 'pending')->count() }}</div>
                    <div class="text-muted small">Pending</div>
                </div>
                <div class="col-md-2">
                    <div class="h4 text-danger mb-1">{{ $tasks->filter(function($task) { return $task->isOverdue(); })->count() }}</div>
                    <div class="text-muted small">Overdue</div>
                </div>
                <div class="col-md-2">
                    <div class="h4 text-info mb-1">{{ number_format($tasks->sum(function($task) { return $task->totalTimeSpent(); }) / 60, 1) }}h</div>
                    <div class="text-muted small">Total Time</div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
