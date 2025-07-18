<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'deadline',
        'status',
        'progress',
        'priority',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
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
        return $this->deadline->isPast() && $this->status !== 'completed';
    }

    public function daysUntilDeadline(): int
    {
        return now()->diffInDays($this->deadline, false);
    }

    public function completedTasksCount(): int
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    public function totalTasksCount(): int
    {
        return $this->tasks()->count();
    }
}
