@extends('layouts.devtime')

@section('title', 'Log Activity - DevTime')
@section('page-title', 'Log New Activity')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Activity Description</label>
                        <textarea class="form-control wysiwyg-editor @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Describe what you worked on..." required>{{ old('description') }}</textarea>
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
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                @if(old('project_id'))
                                    @foreach($tasks->where('project_id', old('project_id')) as $task)
                                        <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                            {{ $task->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('task_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="activity_date" class="form-label">Activity Date</label>
                            <input type="date" class="form-control @error('activity_date') is-invalid @enderror" 
                                   id="activity_date" name="activity_date" 
                                   value="{{ old('activity_date', date('Y-m-d')) }}" required>
                            @error('activity_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">Start Time (Optional)</label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">End Time (Optional)</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                                   id="duration_minutes" name="duration_minutes" min="1" 
                                   value="{{ old('duration_minutes') }}" required>
                            @error('duration_minutes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Will be auto-calculated if start/end times are provided</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-label">Quick Duration</div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(30)">30m</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(60)">1h</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(120)">2h</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(240)">4h</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDuration(480)">8h</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control wysiwyg-editor @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="2" 
                                  placeholder="Additional notes about this activity...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('activities.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Log Activity</button>
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
    const durationInput = document.getElementById('duration_minutes');
    
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

function setDuration(minutes) {
    document.getElementById('duration_minutes').value = minutes;
}
</script>
@endsection
@endsection
