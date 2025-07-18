<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Activity;
use App\Models\Notification;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user
        $user = User::where('email', 'admin@devtime.com')->first();
        
        if (!$user) {
            $this->command->error('Admin user not found. Please run DefaultUserSeeder first.');
            return;
        }

        // Create sample projects
        $projects = [
            [
                'name' => 'DevTime Application',
                'description' => 'A comprehensive time tracking and project management application built with Laravel.',
                'start_date' => Carbon::now()->subDays(15),
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(30),
                'priority' => 'high',
                'progress' => 75,
                'user_id' => $user->id,
            ],
            [
                'name' => 'E-commerce Platform',
                'description' => 'Building a modern e-commerce platform with Laravel and Vue.js.',
                'start_date' => Carbon::now()->subDays(10),
                'status' => 'in_progress',
                'deadline' => Carbon::now()->addDays(60),
                'priority' => 'medium',
                'progress' => 45,
                'user_id' => $user->id,
            ],
            [
                'name' => 'API Documentation',
                'description' => 'Creating comprehensive API documentation for the DevTime platform.',
                'start_date' => Carbon::now()->subDays(20),
                'status' => 'completed',
                'deadline' => Carbon::now()->subDays(5),
                'priority' => 'low',
                'progress' => 100,
                'user_id' => $user->id,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Create sample tasks for each project
            $tasks = [
                [
                    'title' => 'Set up project structure',
                    'description' => 'Initialize Laravel project with proper folder structure',
                    'status' => 'completed',
                    'priority' => 'high',
                    'due_date' => Carbon::now()->subDays(10),
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Implement authentication',
                    'description' => 'Add user authentication using Laravel Breeze',
                    'status' => 'completed',
                    'priority' => 'high',
                    'due_date' => Carbon::now()->subDays(5),
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Create dashboard',
                    'description' => 'Build a comprehensive dashboard with project statistics',
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'due_date' => Carbon::now()->addDays(3),
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Add time tracking',
                    'description' => 'Implement time tracking functionality for activities',
                    'status' => 'pending',
                    'priority' => 'medium',
                    'due_date' => Carbon::now()->addDays(7),
                    'project_id' => $project->id,
                    'user_id' => $user->id,
                ],
            ];

            foreach ($tasks as $taskData) {
                Task::create($taskData);
            }
        }

        // Create sample activities
        $activities = [
            [
                'title' => 'Project Setup',
                'description' => 'Initial project setup and configuration',
                'activity_date' => Carbon::now()->subDays(2),
                'start_time' => '09:00:00',
                'end_time' => '11:30:00',
                'duration_minutes' => 150,
                'project_id' => Project::where('user_id', $user->id)->first()->id,
                'user_id' => $user->id,
            ],
            [
                'title' => 'Authentication Implementation',
                'description' => 'Working on user authentication system with Laravel Breeze',
                'activity_date' => Carbon::now()->subDays(1),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'duration_minutes' => 120,
                'project_id' => Project::where('user_id', $user->id)->first()->id,
                'user_id' => $user->id,
            ],
            [
                'title' => 'Dashboard Development',
                'description' => 'Building the main dashboard with statistics and charts',
                'activity_date' => Carbon::now(),
                'start_time' => '09:30:00',
                'end_time' => '11:00:00',
                'duration_minutes' => 90,
                'project_id' => Project::where('user_id', $user->id)->first()->id,
                'user_id' => $user->id,
            ],
        ];

        foreach ($activities as $activityData) {
            Activity::create($activityData);
        }

        // Create sample notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'project_id' => Project::where('user_id', $user->id)->first()->id,
                'title' => 'Project Deadline Approaching',
                'message' => 'The DevTime Application project deadline is in 30 days.',
                'type' => 'deadline_warning',
                'is_read' => false,
                'notification_date' => Carbon::now(),
            ],
            [
                'user_id' => $user->id,
                'project_id' => Project::where('user_id', $user->id)->skip(1)->first()->id,
                'title' => 'Task Completed',
                'message' => 'Authentication implementation task has been completed.',
                'type' => 'completed',
                'is_read' => false,
                'notification_date' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Daily Reminder',
                'message' => 'Don\'t forget to log your activities for today.',
                'type' => 'reminder',
                'is_read' => true,
                'notification_date' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($notifications as $notificationData) {
            Notification::create($notificationData);
        }

        $this->command->info('Sample data created for admin user');
    }
}
