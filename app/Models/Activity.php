<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'project_id',
        'task_id',
        'title',
        'description',
        'activity_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'notes'
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Helper method to calculate duration if not set
    public function calculateDuration(): int
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInMinutes($this->end_time);
        }
        return $this->duration_minutes ?? 0;
    }
}
