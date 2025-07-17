# DevTime - Laravel Project Management Application

DevTime is a comprehensive web-based project management application built with Laravel 12.x, designed specifically for managing development projects with advanced features for tracking progress, deadlines, and daily activities.

## Features

### 🚀 Core Features
- **Project Management**: Create, edit, and track development projects with deadlines and progress
- **Task Management**: Organize tasks within projects with status tracking and priority levels
- **Daily Activity Logging**: Track time spent on different tasks and project activities
- **Progress Tracking**: Visual progress indicators for projects and tasks
- **Deadline Notifications**: Get notified about approaching project deadlines
- **Tomorrow Planning**: Plan and schedule tasks for the next day with time estimates

### 📊 Dashboard
- Overview of all projects, tasks, and activities
- Quick stats: total projects, active projects, completed tasks
- Upcoming deadlines and overdue projects alerts
- Recent activities timeline
- Tomorrow's planned tasks

### 🎯 Project Features
- Project status tracking (Planning, In Progress, On Hold, Completed, Cancelled)
- Priority levels (Low, Medium, High, Urgent)
- Progress percentage tracking
- Start date and deadline management
- Notes and project descriptions
- Task completion tracking within projects

### ✅ Task Management
- Task status workflow (To Do, In Progress, Review, Completed)
- Due date tracking with overdue indicators
- Estimated vs actual hours tracking
- Task priority management
- Project association

### 📝 Activity Logging
- Daily activity tracking with time logging
- Activity types (Development, Testing, Meeting, Research, Documentation, Other)
- Duration tracking in minutes
- Project and task association
- Activity timeline view

### 📅 Tomorrow Planning
- Schedule tasks for the next day
- Time slot planning with start/end times
- Duration estimation
- Priority-based planning
- Completion tracking

## Technology Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Database**: MySQL (configurable to SQLite for development)
- **Frontend**: Blade Templates with Bootstrap 5.3
- **Icons**: Font Awesome 6.0
- **Styling**: Custom CSS with gradient sidebars and modern cards

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL database
- Node.js and npm (optional, for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd DevTimeLaravel
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   
   Update the `.env` file with your database credentials:
   ```env
   APP_NAME=DevTime
   APP_URL=http://localhost:8000
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=devtime
   DB_USERNAME=devtime
   DB_PASSWORD=devtime
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Sample Data (Optional)**
   ```bash
   php artisan db:seed --class=ProjectSeeder
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

   Access the application at: `http://localhost:8000`

## Docker Installation (Recommended)

For the easiest setup, use Docker to run the application with all dependencies:

### Prerequisites
- Docker and Docker Compose installed on your system

### Quick Setup
1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd DevTimeLaravel
   ```

2. **Run the Docker setup script**
   ```bash
   ./docker-setup.sh
   ```

   This script will:
   - Create and configure the `.env` file
   - Build Docker containers for the app, database, and phpMyAdmin
   - Run database migrations
   - Seed sample data
   - Set proper permissions

3. **Access the application**
   - Application: `http://localhost:8000`
   - Database Admin (phpMyAdmin): `http://localhost:8080`
     - Username: `devtime`
     - Password: `devtime`

### Manual Docker Setup
If you prefer to set up manually:

1. **Create environment file**
   ```bash
   cp .env.example .env
   ```

2. **Update .env for Docker**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=devtime
   DB_USERNAME=devtime
   DB_PASSWORD=devtime
   ```

3. **Build and start containers**
   ```bash
   docker-compose up -d --build
   ```

4. **Run migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

### Docker Commands
- **Stop containers**: `docker-compose down`
- **View logs**: `docker-compose logs -f`
- **Access app container**: `docker-compose exec app bash`
- **Access database**: `docker-compose exec db mysql -u devtime -p devtime`

## Usage

### Creating a Project
1. Click "New Project" from the dashboard or projects page
2. Fill in project details: name, description, start date, deadline
3. Set priority level and initial status
4. Add notes if needed

### Managing Tasks
1. From a project view, click "Add Task"
2. Specify task details, due date, and estimated hours
3. Track progress by updating task status
4. Log actual hours worked

### Logging Activities
1. Navigate to Activities and click "Log Activity"
2. Select project and optionally a task
3. Add description and time details
4. Choose activity type

### Planning Tomorrow
1. Go to Tomorrow Plans section
2. Create new plans with time slots
3. Associate with projects/tasks
4. Track completion the next day

## Docker Support (Coming Soon)

Docker configuration will be added to containerize the application with:
- PHP-FPM container
- MySQL database container
- Nginx web server
- Redis for caching (optional)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**DevTime** - Making project management efficient and insightful for development teams.

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
