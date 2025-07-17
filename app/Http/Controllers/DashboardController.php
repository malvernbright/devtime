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
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'in_progress')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks' => Task::whereIn('status', ['todo', 'in_progress'])->count(),
        ];

        $recent_projects = Project::with('tasks')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $upcoming_deadlines = Project::where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(7))
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $overdue_projects = Project::where('deadline', '<', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $recent_activities = Activity::with(['project', 'task'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unread_notifications = Notification::unread()
            ->with(['project', 'task'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $tomorrow_plans = TomorrowPlan::tomorrow()
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
