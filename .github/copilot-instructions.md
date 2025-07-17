# DevTime Laravel Project - Copilot Instructions

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview

This is a Laravel-based project management application called "DevTime" designed to help manage development projects with the following features:

-   Project management (name, start date, deadline)
-   Task management and tracking
-   Daily activity planning
-   Progress tracking
-   Deadline notifications
-   Tomorrow's task planning with timelines

## Technical Stack

-   **Framework**: Laravel 12.x
-   **Database**: MySQL (username: devtime, password: devtime, database: devtime)
-   **Frontend**: Blade templates with Bootstrap/Tailwind CSS
-   **Authentication**: Laravel Sanctum/Breeze

## Database Schema

The application uses the following main models:

-   `Project`: Manages development projects
-   `Task`: Individual tasks within projects
-   `Activity`: Daily activities and logs
-   `Notification`: Deadline and progress notifications
-   `TomorrowPlan`: Planning tasks for the next day

## Code Style Guidelines

-   Follow PSR-12 coding standards
-   Use Laravel naming conventions
-   Implement proper validation and error handling
-   Use Eloquent relationships effectively
-   Implement proper authorization and authentication
-   Add comprehensive comments for complex logic

## Key Features to Implement

1. Project CRUD operations with deadline tracking
2. Task management with status tracking
3. Daily activity logging
4. Progress visualization
5. Notification system for approaching deadlines
6. Tomorrow's task planning interface
7. Dashboard with project overview
8. Responsive design for mobile and desktop use

When generating code, prioritize:

-   Security best practices
-   Performance optimization
-   Clean, maintainable code
-   Proper error handling
-   User-friendly interfaces
