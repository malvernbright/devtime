#!/bin/bash

# DevTime Docker Setup Script
echo "🚀 Setting up DevTime with Docker..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create environment file for Docker
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    
    # Update .env for Docker setup
    sed -i '' 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i '' 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
    sed -i '' 's/# DB_HOST=127.0.0.1/DB_HOST=db/' .env
    sed -i '' 's/# DB_PORT=3306/DB_PORT=3306/' .env
    sed -i '' 's/# DB_DATABASE=laravel/DB_DATABASE=devtime/' .env
    sed -i '' 's/# DB_USERNAME=root/DB_USERNAME=devtime/' .env
    sed -i '' 's/# DB_PASSWORD=/DB_PASSWORD=devtime/' .env
    
    echo "✅ Environment file created and configured for Docker"
fi

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Build and start Docker containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Run database migrations
echo "📊 Running database migrations..."
docker-compose exec app php artisan migrate --force

# Seed database with sample data
echo "🌱 Seeding database with sample data..."
docker-compose exec app php artisan db:seed --force --class=ProjectSeeder 2>/dev/null || echo "⚠️  Seeding skipped (seeder may not exist)"

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo ""
echo "🎉 DevTime is now running!"
echo ""
echo "📱 Application: http://localhost:8000"
echo "🗄️  Database Admin: http://localhost:8080"
echo "   Username: devtime"
echo "   Password: devtime"
echo ""
echo "🛠️  Useful commands:"
echo "   Stop containers: docker-compose down"
echo "   View logs: docker-compose logs -f"
echo "   Access app container: docker-compose exec app bash"
echo "   Access database: docker-compose exec db mysql -u devtime -p devtime"
echo ""
