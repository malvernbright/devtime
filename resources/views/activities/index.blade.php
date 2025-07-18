@extends('layouts.devtime')

@section('title', 'Activities - DevTime')
@section('page-title', 'Activities')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Log Activity
        </a>
        <a href="#" class="btn btn-outline-success" onclick="exportActivities()">
            <i class="fas fa-file-export me-2"></i>Export Activities
        </a>
    </div>
@endsection

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('activities.index') }}" class="row g-3">
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
            <div class="col-md-3">
                <label for="task_filter" class="form-label">Task</label>
                <select class="form-select" id="task_filter" name="task">
                    <option value="">All Tasks</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ request('task') == $task->id ? 'selected' : '' }}>
                            {{ $task->title }} ({{ $task->project->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Activities List -->
@if($activities->count() > 0)
    <div class="row">
        @foreach($activities as $activity)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-clock text-primary fa-lg"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ strip_tags($activity->description) }}</h6>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @if($activity->project)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-project-diagram me-1"></i>{{ $activity->project->name }}
                                                </span>
                                            @endif
                                            @if($activity->task)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-tasks me-1"></i>{{ $activity->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $activity->activity_date->format('M d, Y') }}
                                            @if($activity->start_time)
                                                <i class="fas fa-play ms-3 me-1"></i>{{ $activity->start_time }}
                                            @endif
                                            @if($activity->end_time)
                                                <i class="fas fa-stop ms-2 me-1"></i>{{ $activity->end_time }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="fw-bold text-success">
                                    @durationShort($activity->duration_minutes)
                                </div>
                                <small class="text-muted">Duration</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('activities.show', $activity) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('activities.edit', $activity) }}" 
                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('activities.destroy', $activity) }}" 
                                          class="d-inline" onsubmit="return confirm('Are you sure you want to delete this activity?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $activities->withQueryString()->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No Activities Found</h5>
            <p class="text-muted mb-4">Start logging your development activities to track your progress.</p>
            <a href="{{ route('activities.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Log Your First Activity
            </a>
        </div>
    </div>
@endif

<!-- Summary Statistics -->
@if($activities->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Summary Statistics</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="h4 text-primary mb-1">{{ $activities->count() }}</div>
                    <div class="text-muted small">Total Activities</div>
                </div>
                <div class="col-md-3">
                    <div class="h4 text-success mb-1">@durationShort($activities->sum('duration_minutes'))</div>
                    <div class="text-muted small">Total Time</div>
                </div>
                <div class="col-md-3">
                    <div class="h4 text-info mb-1">@durationShort($activities->avg('duration_minutes'))</div>
                    <div class="text-muted small">Average Duration</div>
                </div>
                <div class="col-md-3">
                    <div class="h4 text-warning mb-1">{{ $activities->groupBy('project_id')->count() }}</div>
                    <div class="text-muted small">Projects Worked On</div>
                </div>
            </div>
        </div>
    </div>
@endif

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when project filter changes to update task list
    const projectFilter = document.getElementById('project_filter');
    const taskFilter = document.getElementById('task_filter');
    
    projectFilter.addEventListener('change', function() {
        const projectId = this.value;
        
        // Clear task options
        taskFilter.innerHTML = '<option value="">All Tasks</option>';
        
        if (projectId) {
            // Fetch tasks for the selected project
            fetch(`/api/projects/${projectId}/tasks`)
                .then(response => response.json())
                .then(tasks => {
                    tasks.forEach(task => {
                        const option = document.createElement('option');
                        option.value = task.id;
                        option.textContent = task.title;
                        taskFilter.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }
    });

    // Export activities function
    function exportActivities() {
        const activities = @json($activities->toArray());
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Title,Description,Date,Start Time,End Time,Duration (minutes),Project,Task\n";
        
        activities.forEach(activity => {
            const row = [
                activity.title || '',
                activity.description ? activity.description.replace(/"/g, '""') : '',
                activity.activity_date,
                activity.start_time || '',
                activity.end_time || '',
                activity.duration_minutes || '',
                activity.project ? activity.project.name : '',
                activity.task ? activity.task.title : ''
            ].join(',');
            csvContent += row + "\n";
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "activities_export_" + new Date().toISOString().slice(0, 10) + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});
</script>
@endsection
