<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'project_id',
        'task_id',
        'title',
        'message',
        'type',
        'is_read',
        'notification_date'
    ];

    protected $casts = [
        'notification_date' => 'date',
        'is_read' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Scope for unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for today's notifications
    public function scopeToday($query)
    {
        return $query->whereDate('notification_date', today());
    }
}
