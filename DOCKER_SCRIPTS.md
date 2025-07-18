# DevTime Docker Scripts Overview

This directory contains several Docker deployment scripts to help you easily manage your DevTime application.

## Scripts Overview

### 🚀 **quickstart.sh** - First-time Setup
The easiest way to get started with DevTime. Perfect for new users.

```bash
./quickstart.sh
```

**What it does:**
- Checks system requirements
- Validates Docker installation
- Runs complete deployment
- Shows access information

### 🛠️ **deploy.sh** - Full Deployment Management
Comprehensive deployment script with multiple commands for different scenarios.

```bash
./deploy.sh [command]
```

**Available commands:**
- `deploy` - Complete deployment (build + start)
- `build` - Build Docker images
- `start` - Start services
- `stop` - Stop services
- `restart` - Restart services
- `status` - Show container status
- `logs` - View application logs
- `artisan` - Run Laravel artisan commands
- `backup` - Create database backup
- `cleanup` - Clean up Docker resources
- `help` - Show help information

### 🔍 **validate.sh** - System Validation
Validates your Docker environment and configuration before deployment.

```bash
./validate.sh
```

**What it checks:**
- Docker and Docker Compose installation
- Required files existence
- File permissions
- Port availability
- Configuration validity

## Common Usage Patterns

### First-time Setup
```bash
# Validate everything is ready
./validate.sh

# Run quick setup
./quickstart.sh
```

### Development Workflow
```bash
# Build and deploy
./deploy.sh deploy

# View logs during development
./deploy.sh logs

# Run migrations
./deploy.sh artisan migrate

# Create new controllers/models
./deploy.sh artisan make:controller ProjectController
```

### Production Deployment
```bash
# Build and start in production mode
./deploy.sh build
./deploy.sh start

# Check status
./deploy.sh status

# Create backup
./deploy.sh backup
```

### Troubleshooting
```bash
# Check container status
./deploy.sh status

# View logs
./deploy.sh logs

# Restart if needed
./deploy.sh restart

# Clean up and redeploy
./deploy.sh cleanup
./deploy.sh deploy
```

## File Structure

```
DevTimeLaravel/
├── quickstart.sh          # First-time setup script
├── deploy.sh             # Main deployment script
├── validate.sh           # System validation script
├── docker-compose.yml    # Docker services configuration
├── Dockerfile           # Application container definition
├── .env.docker         # Docker environment variables
├── .dockerignore       # Docker build exclusions
├── DOCKER_README.md    # Detailed Docker documentation
└── DOCKER_SCRIPTS.md   # This file
```

## Quick Reference

| Task | Command |
|------|---------|
| First setup | `./quickstart.sh` |
| Full deploy | `./deploy.sh deploy` |
| Start only | `./deploy.sh start` |
| Stop | `./deploy.sh stop` |
| View logs | `./deploy.sh logs` |
| Run migration | `./deploy.sh artisan migrate` |
| Create backup | `./deploy.sh backup` |
| Check status | `./deploy.sh status` |
| Get help | `./deploy.sh help` |

## Access Information

Once deployed, you can access:

- **Main Application**: [http://localhost:8000](http://localhost:8000)
- **PHPMyAdmin**: [http://localhost:8080](http://localhost:8080)
- **Database**: localhost:3307

## Support

If you encounter issues:

1. Run `./validate.sh` to check your setup
2. Check logs with `./deploy.sh logs`
3. Verify container status with `./deploy.sh status`
4. See [DOCKER_README.md](DOCKER_README.md) for detailed troubleshooting

## Tips

- Always run `./validate.sh` before deployment
- Use `./deploy.sh help` to see all available commands
- Check logs regularly during development
- Create backups before major changes
- Use `./deploy.sh cleanup` to free up Docker resources
