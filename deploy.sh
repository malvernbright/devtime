#!/bin/bash

# DevTime Docker Deployment Script
# This script helps you build and deploy the DevTime application using Docker

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if Docker is running
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        print_error "Docker is not running. Please start Docker and try again."
        exit 1
    fi
}

# Function to build the application
build_app() {
    print_status "Building DevTime application..."
    
    # Copy environment file
    if [ ! -f .env ]; then
        print_status "Creating .env file from .env.docker template..."
        cp .env.docker .env
    else
        print_warning ".env file already exists. Skipping environment setup."
    fi
    
    # Build Docker images
    print_status "Building Docker images..."
    docker-compose build --no-cache
    
    print_success "Docker images built successfully!"
}

# Function to start the application
start_app() {
    print_status "Starting DevTime application..."
    
    # Start services
    docker-compose up -d
    
    # Wait for database to be ready
    print_status "Waiting for database to be ready..."
    sleep 10
    
    # Run migrations
    print_status "Running database migrations..."
    docker-compose exec app php artisan migrate --force
    
    # Create storage link
    print_status "Creating storage link..."
    docker-compose exec app php artisan storage:link
    
    # Clear and cache config
    print_status "Optimizing application..."
    docker-compose exec app php artisan config:clear
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    
    print_success "DevTime application started successfully!"
    print_status "Application is available at: http://localhost:8000"
    print_status "PHPMyAdmin is available at: http://localhost:8080"
}

# Function to stop the application
stop_app() {
    print_status "Stopping DevTime application..."
    docker-compose down
    print_success "Application stopped successfully!"
}

# Function to restart the application
restart_app() {
    print_status "Restarting DevTime application..."
    stop_app
    start_app
}

# Function to show logs
show_logs() {
    print_status "Showing application logs..."
    docker-compose logs -f app
}

# Function to run artisan commands
run_artisan() {
    if [ $# -eq 0 ]; then
        print_error "Please provide an artisan command"
        echo "Usage: $0 artisan <command>"
        exit 1
    fi
    
    print_status "Running artisan command: $*"
    docker-compose exec app php artisan "$@"
}

# Function to show application status
show_status() {
    print_status "DevTime Application Status:"
    docker-compose ps
}

# Function to clean up Docker resources
cleanup() {
    print_status "Cleaning up Docker resources..."
    docker-compose down -v
    docker system prune -f
    print_success "Cleanup completed!"
}

# Function to backup database
backup_db() {
    print_status "Creating database backup..."
    BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
    docker-compose exec db mysqldump -u devtime -pdevtime devtime > "$BACKUP_FILE"
    print_success "Database backup created: $BACKUP_FILE"
}

# Function to show help
show_help() {
    echo "🚀 DevTime Docker Deployment Script"
    echo "==================================="
    echo ""
    echo "Usage: $0 [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  build       Build the Docker images"
    echo "  start       Start the application"
    echo "  stop        Stop the application"
    echo "  restart     Restart the application"
    echo "  logs        Show application logs"
    echo "  status      Show application status"
    echo "  artisan     Run artisan commands"
    echo "  backup      Backup database"
    echo "  cleanup     Clean up Docker resources"
    echo "  deploy      Full deployment (build + start)"
    echo "  help        Show this help message"
    echo ""
    echo "Examples:"
    echo "  $0 build"
    echo "  $0 start"
    echo "  $0 deploy"
    echo "  $0 artisan migrate"
    echo "  $0 artisan make:controller TestController"
    echo ""
    echo "📋 Access Information:"
    echo "   Application: http://localhost:8000"
    echo "   PHPMyAdmin:  http://localhost:8080"
    echo "   MySQL Port:  3307 (external)"
    echo ""
    echo "🔑 Database Credentials:"
    echo "   Host:     localhost (or 'db' from within containers)"
    echo "   Port:     3307 (external) / 3306 (internal)"
    echo "   Database: devtime"
    echo "   Username: devtime"
    echo "   Password: devtime"
}

# Function for full deployment
deploy_app() {
    print_status "Starting full deployment..."
    
    # Stop existing containers
    print_status "Stopping existing containers..."
    docker-compose down
    
    # Build and start
    build_app
    start_app
    
    # Show final status
    show_status
    
    print_success "Full deployment completed successfully!"
}

# Main script logic
main() {
    # Check if Docker is running
    check_docker
    
    # Handle commands
    case ${1:-help} in
        build)
            build_app
            ;;
        start)
            start_app
            ;;
        stop)
            stop_app
            ;;
        restart)
            restart_app
            ;;
        logs)
            show_logs
            ;;
        status)
            show_status
            ;;
        artisan)
            shift
            run_artisan "$@"
            ;;
        backup)
            backup_db
            ;;
        cleanup)
            cleanup
            ;;
        deploy)
            deploy_app
            ;;
        help|--help|-h)
            show_help
            ;;
        *)
            print_error "Unknown command: $1"
            show_help
            exit 1
            ;;
    esac
}

# Run the main function
main "$@"
