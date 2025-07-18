#!/bin/bash

# DevTime Alternative Docker Deployment Script
# This script fixes the Laravel Pail issue and uses a simplified Docker setup

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker and try again."
    exit 1
fi

print_status "Starting DevTime deployment with Laravel Pail fix..."

# Stop existing containers
print_status "Stopping existing containers..."
docker-compose -f docker-compose.simple.yml down 2>/dev/null || true

# Temporarily remove Laravel Pail from composer.json to avoid Docker issues
print_status "Temporarily removing Laravel Pail from composer.json..."
cp composer.json composer.json.backup
sed -i.tmp 's/"laravel\/pail": ".*",//g' composer.json

# Build and start containers
print_status "Building and starting containers..."
docker-compose -f docker-compose.simple.yml up -d --build

# Wait for database to be ready
print_status "Waiting for database to be ready..."
sleep 15

# Restore original composer.json
print_status "Restoring original composer.json..."
mv composer.json.backup composer.json
rm -f composer.json.tmp

# Install dependencies inside the container (including Laravel Pail)
print_status "Installing dependencies inside container..."
docker-compose -f docker-compose.simple.yml exec app composer install --optimize-autoloader

# Run migrations
print_status "Running database migrations..."
docker-compose -f docker-compose.simple.yml exec app php artisan migrate --force

# Create storage link
print_status "Creating storage link..."
docker-compose -f docker-compose.simple.yml exec app php artisan storage:link

# Clear and cache configuration
print_status "Optimizing application..."
docker-compose -f docker-compose.simple.yml exec app php artisan config:clear
docker-compose -f docker-compose.simple.yml exec app php artisan config:cache
docker-compose -f docker-compose.simple.yml exec app php artisan route:cache
docker-compose -f docker-compose.simple.yml exec app php artisan view:cache

# Show container status
print_status "Container status:"
docker-compose -f docker-compose.simple.yml ps

print_success "DevTime deployment completed successfully!"
print_status "Application is available at: http://localhost:8000"
print_status "PHPMyAdmin is available at: http://localhost:8080"
print_status ""
print_status "To manage the application:"
print_status "  View logs: docker-compose -f docker-compose.simple.yml logs -f app"
print_status "  Stop: docker-compose -f docker-compose.simple.yml down"
print_status "  Restart: docker-compose -f docker-compose.simple.yml restart"
