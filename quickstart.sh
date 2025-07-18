#!/bin/bash

# DevTime Quick Start Script
# This script sets up DevTime for first-time users

set -e

echo "🚀 DevTime Quick Start Setup"
echo "============================"

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
print_status "Checking Docker installation..."
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker Desktop first."
    echo "Download from: https://www.docker.com/products/docker-desktop"
    exit 1
fi

if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker Desktop and try again."
    exit 1
fi

print_success "Docker is running!"

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose."
    exit 1
fi

print_success "Docker Compose is available!"

# Make deploy script executable
print_status "Making deployment script executable..."
chmod +x deploy.sh

# Run the deployment
print_status "Starting DevTime deployment..."
./deploy.sh deploy

echo ""
echo "🎉 DevTime is now running!"
echo "=========================="
echo ""
echo "📱 Access your application:"
echo "   Main App:    http://localhost:8000"
echo "   PHPMyAdmin:  http://localhost:8080"
echo ""
echo "🔧 Useful commands:"
echo "   View logs:    ./deploy.sh logs"
echo "   Stop app:     ./deploy.sh stop"
echo "   Restart app:  ./deploy.sh restart"
echo "   Show status:  ./deploy.sh status"
echo "   Get help:     ./deploy.sh help"
echo ""
echo "📖 For more information, see DOCKER_README.md"
echo ""
print_success "Setup complete! Happy time tracking! 🕐"
