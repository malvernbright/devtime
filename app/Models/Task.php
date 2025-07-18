<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function tomorrowPlans(): HasMany
    {
        return $this->hasMany(TomorrowPlan::class);
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function daysUntilDue(): int
    {
        return $this->due_date ? now()->diffInDays($this->due_date, false) : 0;
    }

    public function totalTimeSpent(): int
    {
        return $this->activities()->sum('duration_minutes');
    }
}
