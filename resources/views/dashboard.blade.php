@extends('layouts.app')

@section('title', 'Dashboard - DevTime')
@section('page-title', 'Dashboard')

@section('page-actions')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-file-export me-1"></i>Quick Export
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('reports.daily') }}?date={{ date('Y-m-d') }}&consultant=Malvern">
            <i class="fas fa-calendar-day me-2"></i>Today's Report
        </a></li>
        <li><a class="dropdown-item" href="{{ route('reports.planned') }}?date={{ date('Y-m-d', strtotime('+1 day')) }}&consultant=Malvern">
            <i class="fas fa-calendar-alt me-2"></i>Tomorrow's Plan
        </a></li>
        <li><a class="dropdown-item" href="{{ route('reports.status') }}?date={{ date('Y-m-d') }}&location=ZAMBIA OPERATIONS">
            <i class="fas fa-project-diagram me-2"></i>Project Status
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" onclick="showReportModal()">
            <i class="fas fa-cog me-2"></i>Custom Export
        </a></li>
    </ul>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Projects
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_projects'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Active Projects
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_projects'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-play fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Tasks
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_tasks'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Tasks
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_tasks'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Projects -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Projects</h6>
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recent_projects->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_projects as $project)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none">
                                            {{ $project->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        Deadline: {{ $project->deadline->format('M d, Y') }}
                                        ({{ $project->daysUntilDeadline() }} days)
                                    </small>
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ $project->progress }}%" 
                                             aria-valuenow="{{ $project->progress }}" 
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <span class="badge status-{{ $project->status }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No projects yet. <a href="{{ route('projects.create') }}">Create one now</a>!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-warning">Upcoming Deadlines</h6>
            </div>
            <div class="card-body">
                @if($upcoming_deadlines->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcoming_deadlines as $project)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">{{ $project->name }}</h6>
                                    <small class="text-muted">{{ $project->deadline->format('M d, Y') }}</small>
                                </div>
                                <span class="badge {{ $project->daysUntilDeadline() <= 2 ? 'bg-danger' : 'bg-warning' }}">
                                    {{ $project->daysUntilDeadline() }} days
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No upcoming deadlines</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Overdue Projects -->
    @if($overdue_projects->count() > 0)
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-danger">Overdue Projects</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($overdue_projects as $project)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">{{ $project->name }}</h6>
                                <small class="text-muted">Due: {{ $project->deadline->format('M d, Y') }}</small>
                            </div>
                            <span class="badge bg-danger">
                                {{ abs($project->daysUntilDeadline()) }} days overdue
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tomorrow's Plans -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">Tomorrow's Plans</h6>
                <a href="{{ route('tomorrow-plans.create') }}" class="btn btn-sm btn-info">Add Plan</a>
            </div>
            <div class="card-body">
                @if($tomorrow_plans->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($tomorrow_plans as $plan)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <h6 class="mb-1">{{ $plan->title }}</h6>
                                    <small class="text-muted">
                                        @if($plan->start_time)
                                            {{ $plan->start_time }}
                                            @if($plan->end_time)
                                                - {{ $plan->end_time }}
                                            @endif
                                        @endif
                                        @if($plan->project)
                                            | {{ $plan->project->name }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge priority-{{ $plan->priority }}">{{ ucfirst($plan->priority) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No plans for tomorrow. <a href="{{ route('tomorrow-plans.create') }}">Create some</a>!</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                <a href="{{ route('activities.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recent_activities->count() > 0)
                    <div class="timeline">
                        @foreach($recent_activities as $activity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $activity->title }}</h6>
                                    <p class="mb-1 text-muted">{{ Str::limit($activity->description, 100) }}</p>
                                    <small class="text-muted">
                                        {{ $activity->activity_date->format('M d, Y') }}
                                        @if($activity->duration_minutes)
                                            | @durationShort($activity->duration_minutes)
                                        @endif
                                        @if($activity->project)
                                            | {{ $activity->project->name }}
                                        @else
                                            | No project assigned
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">No activities logged yet. <a href="{{ route('activities.create') }}">Log your first activity</a>!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
