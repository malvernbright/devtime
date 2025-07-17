@extends('layouts.app')

@section('title', $tomorrowPlan->title . ' - Tomorrow Plans')
@section('page-title', $tomorrowPlan->title)

@section('page-actions')
    <div class="d-flex gap-2">
        @if(!$tomorrowPlan->is_completed)
            <form method="POST" action="{{ route('tomorrow-plans.complete', $tomorrowPlan) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-2"></i>Mark Completed
                </button>
            </form>
        @endif
        <a href="{{ route('tomorrow-plans.edit', $tomorrowPlan) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Plan
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Plan Details</h5>
            </div>
            <div class="card-body">
                @if($tomorrowPlan->description)
                    <p class="text-muted">{{ $tomorrowPlan->description }}</p>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6>Schedule Information</h6>
                        <ul class="list-unstyled">
                            <li><strong>Planned Date:</strong> 
                                <span class="fw-bold">
                                    {{ $tomorrowPlan->planned_date->format('M d, Y') }}
                                    @if($tomorrowPlan->planned_date->isToday())
                                        <span class="badge bg-info">Today</span>
                                    @elseif($tomorrowPlan->planned_date->isTomorrow())
                                        <span class="badge bg-success">Tomorrow</span>
                                    @elseif($tomorrowPlan->planned_date->isPast())
                                        <span class="badge bg-danger">Past due</span>
                                    @endif
                                </span>
                            </li>
                            
                            @if($tomorrowPlan->start_time)
                                <li><strong>Start Time:</strong> {{ $tomorrowPlan->start_time }}</li>
                            @endif
                            
                            @if($tomorrowPlan->end_time)
                                <li><strong>End Time:</strong> {{ $tomorrowPlan->end_time }}</li>
                            @endif
                            
                            @if($tomorrowPlan->estimated_duration)
                                <li><strong>Duration:</strong> {{ $tomorrowPlan->estimated_duration }} minutes</li>
                            @endif
                            
                            <li><strong>Priority:</strong> 
                                <span class="badge priority-{{ $tomorrowPlan->priority }}">
                                    {{ ucfirst($tomorrowPlan->priority) }}
                                </span>
                            </li>
                            
                            <li><strong>Status:</strong> 
                                @if($tomorrowPlan->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Associations</h6>
                        <ul class="list-unstyled">
                            @if($tomorrowPlan->project)
                                <li><strong>Project:</strong> 
                                    <a href="{{ route('projects.show', $tomorrowPlan->project) }}" class="text-decoration-none">
                                        {{ $tomorrowPlan->project->name }}
                                    </a>
                                </li>
                            @else
                                <li><strong>Project:</strong> <span class="text-muted">Not associated</span></li>
                            @endif
                            
                            @if($tomorrowPlan->task)
                                <li><strong>Task:</strong> 
                                    <a href="{{ route('tasks.show', $tomorrowPlan->task) }}" class="text-decoration-none">
                                        {{ $tomorrowPlan->task->title }}
                                    </a>
                                </li>
                            @else
                                <li><strong>Task:</strong> <span class="text-muted">Not associated</span></li>
                            @endif
                            
                            <li><strong>Created:</strong> {{ $tomorrowPlan->created_at->format('M d, Y H:i') }}</li>
                            <li><strong>Updated:</strong> {{ $tomorrowPlan->updated_at->format('M d, Y H:i') }}</li>
                        </ul>
                    </div>
                </div>

                @if($tomorrowPlan->start_time && $tomorrowPlan->end_time)
                    <div class="mt-4">
                        <h6>Time Breakdown</h6>
                        <div class="alert alert-light">
                            <i class="fas fa-clock me-2"></i>
                            Scheduled from {{ $tomorrowPlan->start_time }} 
                            to {{ $tomorrowPlan->end_time }}
                        </div>
                    </div>
                @endif
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
                    @if(!$tomorrowPlan->is_completed)
                        <form method="POST" action="{{ route('tomorrow-plans.complete', $tomorrowPlan) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check me-2"></i>Mark as Completed
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('tomorrow-plans.edit', $tomorrowPlan) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Plan
                    </a>
                    
                    @if($tomorrowPlan->project)
                        <a href="{{ route('projects.show', $tomorrowPlan->project) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-project-diagram me-2"></i>View Project
                        </a>
                    @endif
                    
                    @if($tomorrowPlan->task)
                        <a href="{{ route('tasks.show', $tomorrowPlan->task) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-tasks me-2"></i>View Task
                        </a>
                    @endif
                    
                    <a href="{{ route('activities.create') }}?project_id={{ $tomorrowPlan->project_id ?? '' }}&task_id={{ $tomorrowPlan->task_id ?? '' }}" 
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-clock me-2"></i>Log Activity
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Navigation</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('tomorrow-plans.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list me-2"></i>All Plans
                    </a>
                    <a href="{{ route('tomorrow-plans.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create New Plan
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
