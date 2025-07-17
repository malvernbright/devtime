#!/bin/bash

echo "🚀 DevTime Laravel Application - Docker Deployment"
echo "=================================================="

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

echo "✅ Docker is running"

# Stop existing containers if they exist
echo "🛑 Stopping existing containers..."
docker-compose down

# Build and start the application
echo "🔨 Building and starting containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 30

# Run migrations and seed the database
echo "🗃️  Running database migrations and seeding..."
docker-compose exec app php artisan migrate:fresh --seed --force

# Clear caches
echo "🧹 Clearing caches..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear

# Show status
echo "📊 Container status:"
docker-compose ps

echo ""
echo "🎉 Deployment complete!"
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
echo ""
echo "📖 Useful Commands:"
echo "   View logs:           docker-compose logs -f"
echo "   Stop application:    docker-compose down"
echo "   Restart application: docker-compose restart"
echo "   Access shell:        docker-compose exec app bash"
