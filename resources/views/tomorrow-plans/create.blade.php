@extends('layouts.app')

@section('title', 'Create Tomorrow Plan - DevTime')
@section('page-title', 'Create Tomorrow Plan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tomorrow-plans.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Plan Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="project_id" class="form-label">Project (Optional)</label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" 
                                            {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="task_id" class="form-label">Task (Optional)</label>
                            <select class="form-select @error('task_id') is-invalid @enderror" 
                                    id="task_id" name="task_id">
                                <option value="">Select Task</option>
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}" 
                                            {{ old('task_id', request('task_id')) == $task->id ? 'selected' : '' }}>
                                        {{ $task->title }} ({{ $task->project->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('task_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="planned_date" class="form-label">Planned Date</label>
                            <input type="date" class="form-control @error('planned_date') is-invalid @enderror" 
                                   id="planned_date" name="planned_date" 
                                   value="{{ old('planned_date', now()->addDay()->format('Y-m-d')) }}" required>
                            @error('planned_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estimated_duration" class="form-label">Estimated Duration (minutes)</label>
                        <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror" 
                               id="estimated_duration" name="estimated_duration" min="1" 
                               value="{{ old('estimated_duration') }}">
                        @error('estimated_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank if using start/end times</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tomorrow-plans.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('project_id');
    const taskSelect = document.getElementById('task_id');
    
    projectSelect.addEventListener('change', function() {
        const projectId = this.value;
        
        // Clear existing task options
        taskSelect.innerHTML = '<option value="">Select Task</option>';
        
        if (projectId) {
            // Fetch tasks for the selected project
            fetch(`/api/projects/${projectId}/tasks`)
                .then(response => response.json())
                .then(tasks => {
                    tasks.forEach(task => {
                        const option = document.createElement('option');
                        option.value = task.id;
                        option.textContent = task.title;
                        taskSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }
    });
    
    // Auto-calculate duration when times change
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const durationInput = document.getElementById('estimated_duration');
    
    function calculateDuration() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date(`2000-01-01T${startTimeInput.value}`);
            const end = new Date(`2000-01-01T${endTimeInput.value}`);
            
            if (end > start) {
                const diffMs = end - start;
                const diffMins = Math.round(diffMs / (1000 * 60));
                durationInput.value = diffMins;
            }
        }
    }
    
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
});
</script>
@endsection
@endsection
