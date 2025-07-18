@extends('layouts.devtime')

@section('title', 'Create Task - DevTime')
@section('page-title', 'Create New Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Task Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
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
                                        {{ (old('project_id', $selectedProjectId) == $project->id) ? 'selected' : '' }}>
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
                                  id="description" name="description" rows="5" 
                                  data-tinymce="true"
                                  placeholder="Describe the task in detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="due_date" class="form-label">Due Date (Optional)</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="estimated_hours" class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" 
                                   id="estimated_hours" name="estimated_hours" min="0.5" step="0.5" 
                                   value="{{ old('estimated_hours') }}" placeholder="e.g., 2.5">
                            @error('estimated_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function quickSetup(type) {
    const titleInput = document.getElementById('title');
    const prioritySelect = document.getElementById('priority');
    const statusSelect = document.getElementById('status');
    const estimatedHoursInput = document.getElementById('estimated_hours');
    
    switch(type) {
        case 'bug':
            if (!titleInput.value) titleInput.value = 'Fix bug: ';
            prioritySelect.value = 'high';
            statusSelect.value = 'pending';
            estimatedHoursInput.value = '2';
            break;
        case 'feature':
            if (!titleInput.value) titleInput.value = 'Implement ';
            prioritySelect.value = 'medium';
            statusSelect.value = 'pending';
            estimatedHoursInput.value = '4';
            break;
        case 'improvement':
            if (!titleInput.value) titleInput.value = 'Improve ';
            prioritySelect.value = 'low';
            statusSelect.value = 'pending';
            estimatedHoursInput.value = '3';
            break;
        case 'documentation':
            if (!titleInput.value) titleInput.value = 'Document ';
            prioritySelect.value = 'low';
            statusSelect.value = 'pending';
            estimatedHoursInput.value = '1';
            break;
    }
    
    // Focus on title input for user to complete
    titleInput.focus();
    titleInput.setSelectionRange(titleInput.value.length, titleInput.value.length);
}
</script>
@endsection
@endsection
