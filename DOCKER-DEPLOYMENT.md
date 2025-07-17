# DevTime Laravel - Docker Deployment Guide

## 🚀 Quick Start

To deploy the DevTime Laravel application using Docker:

```bash
# Make deploy script executable (if not already)
chmod +x deploy.sh

# Run the deployment script
./deploy.sh
```

## 📋 Manual Deployment

If you prefer to deploy manually:

```bash
# 1. Build and start containers
docker-compose up -d --build

# 2. Wait for MySQL to initialize (about 30 seconds)
sleep 30

# 3. Run migrations and seed database
docker-compose exec app php artisan migrate:fresh --seed --force

# 4. Clear caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

## 🔧 Configuration

### Ports
- **Application**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **MySQL**: localhost:3307 (external), db:3306 (internal)

### Database Credentials
- **Host**: db (internal) / localhost:3307 (external)
- **Database**: devtime
- **Username**: devtime
- **Password**: devtime
- **Root Password**: root_password

## 📊 Management Commands

```bash
# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f db

# Stop application
docker-compose down

# Restart application
docker-compose restart

# Access application shell
docker-compose exec app bash

# Access MySQL shell
docker-compose exec db mysql -u devtime -pdevtime devtime

# Run Laravel commands
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

## 🔄 Updates and Maintenance

```bash
# Update application code and redeploy
git pull
docker-compose down
docker-compose up -d --build

# Backup database
docker-compose exec db mysqldump -u devtime -pdevtime devtime > backup.sql

# Restore database
docker-compose exec -T db mysql -u devtime -pdevtime devtime < backup.sql
```

## 🐛 Troubleshooting

### Port Conflicts
If you get port conflicts:
- Change ports in `docker-compose.yml`
- Make sure no other services are using ports 8000, 8080, or 3307

### Database Connection Issues
- Ensure MySQL container is fully started before running migrations
- Check if your local MySQL is conflicting (it uses port 3306, Docker uses 3307)

### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

## 🚀 Production Deployment

For production servers:

1. **Update environment variables** in `docker-compose.yml`
2. **Set secure database passwords**
3. **Configure domain names** instead of localhost
4. **Set up SSL/TLS** using a reverse proxy like Nginx
5. **Configure backups** for database and storage

### Production Security Checklist
- [ ] Change all default passwords
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure proper logging
- [ ] Set up SSL certificates
- [ ] Configure firewall rules
- [ ] Set up automated backups

## 📈 Monitoring

The containers include health checks that monitor:
- Application HTTP response
- MySQL database connectivity

Check health status:
```bash
docker-compose ps
```

## 🔐 Security Notes

- Default credentials are for development only
- Change all passwords before production deployment
- The application runs with minimal privileges
- Database is isolated within Docker network
- Consider using Docker secrets for sensitive data in production
