<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TomorrowPlanController;
use App\Http\Controllers\ReportController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Projects
Route::resource('projects', ProjectController::class);

// Tasks
Route::resource('tasks', TaskController::class);

// Activities
Route::resource('activities', ActivityController::class);

// Tomorrow Plans
Route::resource('tomorrow-plans', TomorrowPlanController::class);
Route::patch('tomorrow-plans/{tomorrow_plan}/complete', [TomorrowPlanController::class, 'markCompleted'])
    ->name('tomorrow-plans.complete');

// Report Exports
Route::get('reports/daily-report', [ReportController::class, 'exportDailyReport'])->name('reports.daily');
Route::get('reports/planned-tasks', [ReportController::class, 'exportPlannedTasks'])->name('reports.planned');
Route::get('reports/project-status', [ReportController::class, 'exportProjectStatus'])->name('reports.status');

// API Routes for AJAX calls
Route::prefix('api')->group(function () {
    Route::get('projects/{project}/tasks', function ($id) {
        return \App\Models\Project::find($id)->tasks;
    });
    
    Route::patch('notifications/{notification}/read', function ($id) {
        $notification = \App\Models\Notification::find($id);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });
});
