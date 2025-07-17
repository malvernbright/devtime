@extends('layouts.app')

@section('title', 'Tomorrow Plans - DevTime')
@section('page-title', 'Tomorrow Plans')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('tomorrow-plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Plan
        </a>
        <a href="{{ route('reports.planned') }}?date={{ request('date', date('Y-m-d', strtotime('+1 day'))) }}&consultant=Malvern" class="btn btn-outline-success">
            <i class="fas fa-file-word me-2"></i>Export Planned Tasks
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    @if($plans->count() > 0)
        @foreach($plans as $plan)
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">{{ $plan->title }}</h6>
                        <div class="d-flex gap-2">
                            <span class="badge priority-{{ $plan->priority }}">
                                {{ ucfirst($plan->priority) }}
                            </span>
                            @if($plan->is_completed)
                                <span class="badge bg-success">Completed</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($plan->description)
                            <p class="text-muted">{{ Str::limit($plan->description, 100) }}</p>
                        @endif
                        
                        <div class="mb-3">
                            <small class="text-muted">Planned Date</small>
                            <div class="fw-bold">{{ $plan->planned_date->format('M d, Y') }}</div>
                            @if($plan->planned_date->isToday())
                                <small class="text-info">Today</small>
                            @elseif($plan->planned_date->isTomorrow())
                                <small class="text-success">Tomorrow</small>
                            @elseif($plan->planned_date->isPast())
                                <small class="text-danger">Past due</small>
                            @endif
                        </div>

                        @if($plan->start_time || $plan->end_time)
                            <div class="mb-3">
                                <small class="text-muted">Time</small>
                                <div class="fw-bold">
                                    @if($plan->start_time)
                                        {{ $plan->start_time }}
                                        @if($plan->end_time)
                                            - {{ $plan->end_time }}
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($plan->estimated_duration)
                            <div class="mb-3">
                                <small class="text-muted">Duration</small>
                                <div class="fw-bold">{{ $plan->estimated_duration }} minutes</div>
                            </div>
                        @endif

                        @if($plan->project)
                            <div class="mb-3">
                                <small class="text-muted">Project</small>
                                <div>
                                    <a href="{{ route('projects.show', $plan->project) }}" class="text-decoration-none">
                                        {{ $plan->project->name }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($plan->task)
                            <div class="mb-3">
                                <small class="text-muted">Task</small>
                                <div>
                                    <a href="{{ route('tasks.show', $plan->task) }}" class="text-decoration-none">
                                        {{ $plan->task->title }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('tomorrow-plans.show', $plan) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('tomorrow-plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </div>
                            <div class="d-flex gap-2">
                                @if(!$plan->is_completed)
                                    <form method="POST" action="{{ route('tomorrow-plans.complete', $plan) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Mark as completed">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('tomorrow-plans.destroy', $plan) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this plan?')"
                                            title="Delete plan">
                                        <i class="fas fa-trash"></i>
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
                <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No plans yet</h4>
                <p class="text-muted">Create your first plan to get started!</p>
                <a href="{{ route('tomorrow-plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Plan
                </a>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-12">
        {{ $plans->links() }}
    </div>
</div>

<!-- Quick Filter Buttons -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Quick Filters</h6>
                <div class="btn-group" role="group">
                    <a href="{{ route('tomorrow-plans.index') }}" class="btn btn-outline-secondary btn-sm">
                        All Plans
                    </a>
                    <a href="{{ route('tomorrow-plans.index') }}?filter=today" class="btn btn-outline-info btn-sm">
                        Today
                    </a>
                    <a href="{{ route('tomorrow-plans.index') }}?filter=tomorrow" class="btn btn-outline-success btn-sm">
                        Tomorrow
                    </a>
                    <a href="{{ route('tomorrow-plans.index') }}?filter=pending" class="btn btn-outline-warning btn-sm">
                        Pending
                    </a>
                    <a href="{{ route('tomorrow-plans.index') }}?filter=completed" class="btn btn-outline-primary btn-sm">
                        Completed
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
