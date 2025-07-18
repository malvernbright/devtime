# DevTime - Docker Deployment Guide

## Overview
DevTime is a modern Laravel-based time tracking and project management application with multi-user authentication, rich text editing capabilities, and a responsive design system.

## Features
- 🔐 Multi-user authentication with Laravel Breeze
- 📝 Rich text editing with TinyMCE (Premium features enabled)
- 🎨 Modern UI with gradients and glass morphism effects
- 📱 Responsive design optimized for all devices
- 📊 Comprehensive project and time tracking
- 🐳 Docker containerization for easy deployment
- 💾 Database backup and management tools

## Quick Start

### Prerequisites
- Docker Desktop installed and running
- Git (for cloning the repository)

### 1. Clone the Repository
```bash
git clone <repository-url>
cd DevTimeLaravel
```

### 2. Deploy the Application
```bash
./deploy.sh deploy
```

This single command will:
- Build Docker images
- Start all services
- Run database migrations
- Optimize the application
- Show deployment status

### 3. Access the Application
- **Main Application**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **Database**: localhost:3307

## Manual Deployment Steps

If you prefer to run commands manually:

### 1. Build the Application
```bash
./deploy.sh build
```

### 2. Start Services
```bash
./deploy.sh start
```

### 3. Check Status
```bash
./deploy.sh status
```

## Available Commands

The `deploy.sh` script provides several useful commands:

### Basic Operations
```bash
./deploy.sh build       # Build Docker images
./deploy.sh start       # Start the application
./deploy.sh stop        # Stop the application
./deploy.sh restart     # Restart the application
./deploy.sh deploy      # Full deployment (build + start)
```

### Monitoring & Debugging
```bash
./deploy.sh logs        # Show application logs
./deploy.sh status      # Show container status
```

### Database Operations
```bash
./deploy.sh backup      # Create database backup
./deploy.sh artisan migrate         # Run migrations
./deploy.sh artisan migrate:fresh   # Fresh migrations
./deploy.sh artisan db:seed         # Seed database
```

### Development Commands
```bash
./deploy.sh artisan make:controller UserController
./deploy.sh artisan make:model Project
./deploy.sh artisan make:migration create_tasks_table
./deploy.sh artisan tinker
```

### Cleanup
```bash
./deploy.sh cleanup     # Clean up Docker resources
```

## Configuration

### Environment Variables
The application uses environment variables defined in `.env.docker`. Key configurations include:

```env
# Application
APP_NAME="DevTime"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=devtime
DB_USERNAME=devtime
DB_PASSWORD=devtime

# TinyMCE
TINYMCE_API_KEY=7djntdu3j15dfkxrgs17jd2t47v41rrcdqkd1hpbhr6wj327

# Asset Compilation
VITE_APP_NAME="DevTime"
```

### Docker Services
The application runs several Docker services:

- **app**: Laravel application (PHP 8.2, Nginx)
- **db**: MySQL 8.0 database
- **phpmyadmin**: Database management interface

## Database Access

### Credentials
- **Host**: localhost (external) / db (internal)
- **Port**: 3307 (external) / 3306 (internal)
- **Database**: devtime
- **Username**: devtime
- **Password**: devtime

### PHPMyAdmin Access
1. Open http://localhost:8080
2. Login with the database credentials above
3. Select the `devtime` database

## Troubleshooting

### Common Issues

#### 1. Port Already in Use
If you get port conflicts:
```bash
./deploy.sh stop
./deploy.sh start
```

#### 2. Database Connection Issues
Wait for MySQL to fully start:
```bash
./deploy.sh logs
# Wait for "ready for connections" message
```

#### 3. Permission Issues
Make sure the deploy script is executable:
```bash
chmod +x deploy.sh
```

#### 4. Docker Not Running
Start Docker Desktop and verify:
```bash
docker info
```

### Viewing Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
```

### Accessing Container Shell
```bash
# Application container
docker-compose exec app bash

# Database container
docker-compose exec db bash
```

## Development Workflow

### Making Changes
1. Edit your code files
2. Restart the application:
   ```bash
   ./deploy.sh restart
   ```

### Database Changes
1. Create migration:
   ```bash
   ./deploy.sh artisan make:migration create_new_table
   ```

2. Run migrations:
   ```bash
   ./deploy.sh artisan migrate
   ```

### Asset Compilation
Assets are automatically compiled during the Docker build process using Vite. The build includes:
- CSS compilation and minification
- JavaScript bundling
- Asset optimization

## Backup and Recovery

### Create Backup
```bash
./deploy.sh backup
```

### Restore from Backup
```bash
# Copy backup file to container
docker-compose exec db mysql -u devtime -pdevtime devtime < backup_file.sql
```

## Production Considerations

### Security
- Change default database passwords
- Update APP_KEY in production
- Set APP_DEBUG=false
- Use HTTPS in production

### Performance
- Enable Redis for caching
- Use production web server (Apache/Nginx)
- Optimize database queries
- Enable application caching

### Monitoring
- Set up log aggregation
- Monitor container health
- Track application performance
- Set up alerts for failures

## Technical Stack

- **Framework**: Laravel 12.20.0
- **PHP**: 8.2
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5.3, TinyMCE 6
- **Build Tool**: Vite
- **Containerization**: Docker & Docker Compose
- **Authentication**: Laravel Breeze

## Support

For issues or questions:
1. Check the logs: `./deploy.sh logs`
2. Verify container status: `./deploy.sh status`
3. Review this documentation
4. Check Docker Desktop for resource usage

## License

This project is open-sourced software licensed under the MIT license.
