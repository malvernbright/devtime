#!/bin/bash

# DevTime Docker Validation Script
# This script validates the Docker deployment setup

set -e

echo "🔍 DevTime Docker Validation"
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

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check Docker requirements
print_status "Checking Docker requirements..."

# Check Docker installation
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed"
    exit 1
fi
print_success "Docker is installed"

# Check Docker Compose installation
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed"
    exit 1
fi
print_success "Docker Compose is installed"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running"
    exit 1
fi
print_success "Docker is running"

# Check required files
print_status "Checking required files..."

required_files=(
    "docker-compose.yml"
    "Dockerfile"
    ".env.docker"
    ".dockerignore"
    "deploy.sh"
    "quickstart.sh"
)

for file in "${required_files[@]}"; do
    if [ ! -f "$file" ]; then
        print_error "Missing required file: $file"
        exit 1
    fi
done
print_success "All required files present"

# Check file permissions
print_status "Checking file permissions..."
if [ ! -x "deploy.sh" ]; then
    print_warning "deploy.sh is not executable, fixing..."
    chmod +x deploy.sh
fi

if [ ! -x "quickstart.sh" ]; then
    print_warning "quickstart.sh is not executable, fixing..."
    chmod +x quickstart.sh
fi

print_success "File permissions are correct"

# Validate Docker Compose configuration
print_status "Validating Docker Compose configuration..."
if ! docker-compose config > /dev/null 2>&1; then
    print_error "Docker Compose configuration is invalid"
    exit 1
fi
print_success "Docker Compose configuration is valid"

# Check port availability
print_status "Checking port availability..."
ports=(8000 8080 3307)
for port in "${ports[@]}"; do
    if lsof -i :$port > /dev/null 2>&1; then
        print_warning "Port $port is already in use"
    else
        print_success "Port $port is available"
    fi
done

# Check if .env file exists
print_status "Checking environment configuration..."
if [ ! -f ".env" ]; then
    print_warning ".env file not found, will be created from .env.docker during deployment"
else
    print_success ".env file exists"
fi

# Summary
echo ""
echo "📋 Validation Summary"
echo "===================="
print_success "Docker setup is valid and ready for deployment"
echo ""
echo "🚀 Next steps:"
echo "   1. Run: ./quickstart.sh (for first-time setup)"
echo "   2. Or run: ./deploy.sh deploy (for manual deployment)"
echo "   3. Access app at: http://localhost:8000"
echo ""
echo "📖 For more information, see DOCKER_README.md"
