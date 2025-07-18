<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TomorrowPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    
    // Tasks
    Route::resource('tasks', TaskController::class);
    
    // Activities
    Route::resource('activities', ActivityController::class);
    
    // Tomorrow Plans
    Route::resource('tomorrow-plans', TomorrowPlanController::class);
});

require __DIR__.'/auth.php';
