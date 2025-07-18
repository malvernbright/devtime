<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TomorrowPlan extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'title',
        'description',
        'planned_date',
        'start_time',
        'end_time',
        'estimated_duration',
        'priority',
        'is_completed'
    ];

    protected $casts = [
        'planned_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Scope for tomorrow's plans
    public function scopeTomorrow($query)
    {
        return $query->whereDate('planned_date', now()->addDay());
    }

    // Scope for incomplete plans
    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }
}
