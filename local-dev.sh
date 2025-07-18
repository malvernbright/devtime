#!/bin/bash

# DevTime Local Development Script
# This script runs the application locally without Docker

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

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js."
    exit 1
fi

print_status "Starting DevTime local development environment..."

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    print_status "Creating .env file..."
    cp .env.example .env
    print_status "Please update your .env file with your database credentials."
fi

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
npm install

# Generate application key if needed
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    print_status "Generating application key..."
    php artisan key:generate
fi

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force

# Create storage link
print_status "Creating storage link..."
php artisan storage:link

# Clear and cache configuration
print_status "Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
print_status "Building assets..."
npm run build

print_success "DevTime local development environment is ready!"
print_status "To start the application, run: php artisan serve"
print_status "The application will be available at: http://localhost:8000"
