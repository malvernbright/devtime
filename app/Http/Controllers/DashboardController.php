<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\TomorrowPlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_projects' => $user->projects()->count(),
            'active_projects' => $user->projects()->where('status', 'in_progress')->count(),
            'completed_projects' => $user->projects()->where('status', 'completed')->count(),
            'total_tasks' => $user->tasks()->count(),
            'completed_tasks' => $user->tasks()->where('status', 'completed')->count(),
            'pending_tasks' => $user->tasks()->whereIn('status', ['todo', 'in_progress'])->count(),
        ];

        $recent_projects = $user->projects()
            ->with('tasks')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $upcoming_deadlines = $user->projects()
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $overdue_projects = $user->projects()
            ->where('deadline', '<', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $recent_activities = $user->activities()
            ->with(['project', 'task'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unread_notifications = $user->notifications()
            ->unread()
            ->with(['project', 'task'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $tomorrow_plans = $user->tomorrowPlans()
            ->tomorrow()
            ->with(['project', 'task'])
            ->orderBy('start_time')
            ->get();

        return view('dashboard', compact(
            'stats',
            'recent_projects',
            'upcoming_deadlines',
            'overdue_projects',
            'recent_activities',
            'unread_notifications',
            'tomorrow_plans'
        ));
    }
}
