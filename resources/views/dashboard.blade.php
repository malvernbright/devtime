@extends('layouts.devtime')

@section('title', 'Dashboard - DevTime')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-project-diagram fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">{{ $stats['total_projects'] }}</h5>
                        <p class="card-text text-muted">Total Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-play-circle fa-2x text-success mb-3"></i>
                        <h5 class="card-title">{{ $stats['active_projects'] }}</h5>
                        <p class="card-text text-muted">Active Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-tasks fa-2x text-info mb-3"></i>
                        <h5 class="card-title">{{ $stats['total_tasks'] }}</h5>
                        <p class="card-text text-muted">Total Tasks</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x text-warning mb-3"></i>
                        <h5 class="card-title">{{ $stats['completed_tasks'] }}</h5>
                        <p class="card-text text-muted">Completed Tasks</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Recent Projects</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recent_projects as $project)
                            <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <div>
                                    <h6 class="mb-1">{{ $project->name }}</h6>
                                    <small class="text-muted">{{ $project->tasks->count() }} tasks</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge status-{{ $project->status }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">No projects yet. <a href="{{ route('projects.create') }}">Create your first project</a></p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Recent Activities</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recent_activities as $activity)
                            <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <div class="me-3">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity->title ?? 'Activity' }}</h6>
                                    <p class="mb-1 text-muted">{{ Str::limit(strip_tags($activity->description), 100) }}</p>
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
                        @empty
                            <p class="text-muted text-center">No activities yet. <a href="{{ route('activities.create') }}">Log your first activity</a></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
