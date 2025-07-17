@extends('layouts.app')

@section('title', 'Edit ' . $task->title . ' - DevTime')
@section('page-title', 'Edit Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Task Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $task->title) }}" 
                                   placeholder="Enter task title..." required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="project_id" class="form-label">Project</label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id" required>
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" 
                                            {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe the task in detail...">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority">
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="due_date" class="form-label">Due Date (Optional)</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" 
                                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="estimated_hours" class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                   id="estimated_hours" name="estimated_hours" min="0.5" step="0.5" 
                                   value="{{ old('estimated_hours', $task->estimated_hours) }}" placeholder="e.g., 2.5">
                            @error('estimated_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Progress Info -->
                    @if($task->activities->count() > 0)
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">Current Progress</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Time Spent:</strong> {{ number_format($task->totalTimeSpent() / 60, 1) }} hours
                                </div>
                                <div class="col-md-4">
                                    <strong>Activities:</strong> {{ $task->activities->count() }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Last Activity:</strong> {{ $task->activities->max('activity_date')->format('M j, Y') }}
                                </div>
                            </div>
                            @if($task->estimated_hours)
                                <div class="progress mt-2" style="height: 8px;">
                                    @php
                                        $percentage = min(100, ($task->totalTimeSpent() / 60) / $task->estimated_hours * 100);
                                    @endphp
                                    <div class="progress-bar {{ $percentage > 100 ? 'bg-danger' : 'bg-success' }}" 
                                         role="progressbar" style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ number_format($percentage, 1) }}% of estimated time
                                    @if($percentage > 100)
                                        <span class="text-danger">(Over budget by {{ number_format($percentage - 100, 1) }}%)</span>
                                    @endif
                                </small>
                            @endif
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mb-3">
                        <label class="form-label">Quick Setup</label>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSetup('bug')">
                                <i class="fas fa-bug me-1"></i>Bug Fix
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSetup('feature')">
                                <i class="fas fa-plus me-1"></i>New Feature
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSetup('improvement')">
                                <i class="fas fa-arrow-up me-1"></i>Improvement
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSetup('documentation')">
                                <i class="fas fa-book me-1"></i>Documentation
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function quickSetup(type) {
    const prioritySelect = document.getElementById('priority');
    const estimatedHoursInput = document.getElementById('estimated_hours');
    
    switch(type) {
        case 'bug':
            prioritySelect.value = 'high';
            if (!estimatedHoursInput.value) estimatedHoursInput.value = '2';
            break;
        case 'feature':
            prioritySelect.value = 'medium';
            if (!estimatedHoursInput.value) estimatedHoursInput.value = '4';
            break;
        case 'improvement':
            prioritySelect.value = 'low';
            if (!estimatedHoursInput.value) estimatedHoursInput.value = '3';
            break;
        case 'documentation':
            prioritySelect.value = 'low';
            if (!estimatedHoursInput.value) estimatedHoursInput.value = '1';
            break;
    }
}
</script>
@endsection
@endsection
