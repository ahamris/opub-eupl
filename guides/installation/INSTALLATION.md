# One-Click Installation Guide

## Overview

The `install.sh` script provides a complete one-click installation of the Open Overheid Platform, setting up all required services automatically.

## What Gets Installed

### 1. Docker & Docker Compose
- Automatically installs Docker if not present
- Supports Ubuntu, Debian, Fedora, RHEL, CentOS, Arch, and macOS
- Adds current user to docker group

### 2. Node.js & npm
- **Node.js v25.2.1 (Current)** - [Download](https://nodejs.org/en/download/current)
- **npm** (included with Node.js)
- Automatically installed via NodeSource repository
- Required for building frontend assets (Vite, Tailwind CSS)

### 3. PostgreSQL 18 (Latest)
- Latest version: PostgreSQL 18.1 ([PostgreSQL.org](https://www.postgresql.org/))
- Runs in Docker container
- Port: `5432`
- Auto-generated secure password
- Persistent data volume

### 4. pgAdmin 4 (Latest)
- Latest version ([pgAdmin.org](https://www.pgadmin.org/))
- Web-based database management UI
- URL: `http://localhost:5050`
- Auto-generated credentials
- Pre-configured to connect to PostgreSQL

### 5. Typesense (Latest)
- Latest version ([Typesense.org](https://typesense.org/))
- Search engine for full-text search
- Port: `8108`
- Auto-generated API key
- Persistent data volume

### 6. Dockge (Latest)
- Latest version ([GitHub](https://github.com/louislam/dockge))
- Docker Compose stack manager
- Port: `5001`
- URL: `http://localhost:5001`
- Manage all your Docker Compose stacks from a web UI

### 7. Laravel Application
- Installs PHP dependencies (Composer)
- Installs Node dependencies (npm)
- Builds frontend assets with Vite
- Configures `.env` file
- Runs database migrations

## Quick Start

```bash
# Make script executable
chmod +x install.sh

# Run installation
./install.sh
```

That's it! The script handles everything automatically.

## What You Get

After installation, you'll have:

1. **All services running** in Docker containers
2. **Credentials file** (`.docker-secrets.txt`) with all passwords
3. **Configured `.env`** file ready to use
4. **Database migrated** and ready

## Access Points

- **Laravel App**: `http://localhost:8000` (after running `php artisan serve`)
- **Dockge**: `http://localhost:5001` (Docker Compose manager)
- **pgAdmin**: `http://localhost:5050`
- **PostgreSQL**: `localhost:5432`
- **Typesense**: `http://localhost:8108`

## Credentials

All credentials are automatically generated and saved to `.docker-secrets.txt`:

```
PostgreSQL:
  Host: localhost
  Port: 5432
  Database: open_overheid
  Username: openoverheid
  Password: [auto-generated]

pgAdmin:
  URL: http://localhost:5050
  Email: admin@openoverheid.local
  Password: [auto-generated]

Typesense:
  URL: http://localhost:8108
  API Key: [auto-generated]
```

## After Installation

1. **Start Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Access the application:**
   - Open browser to `http://localhost:8000`

3. **Access pgAdmin (optional):**
   - Open `http://localhost:5050`
   - Login with credentials from `.docker-secrets.txt`
   - Register PostgreSQL server:
     - Host: `postgres` (from within Docker network) or `localhost` (from host)
     - Port: `5432`
     - Database: `open_overheid`
     - Username/Password: from `.docker-secrets.txt`

## Useful Commands

```bash
# View all service logs
docker compose logs -f

# View specific service logs
docker compose logs -f postgres
docker compose logs -f typesense
docker compose logs -f pgadmin

# Stop all services
docker compose stop

# Start all services
docker compose start

# Restart all services
docker compose restart

# Stop and remove containers (keeps data)
docker compose down

# Stop and remove everything including data
docker compose down -v
```

## Troubleshooting

### Docker Group Permission
If you get permission errors, you may need to:
```bash
# Apply docker group without logging out
newgrp docker

# Or log out and back in
```

### Port Already in Use
If ports 5432, 5050, or 8108 are already in use:
- Stop the conflicting service, or
- Edit `docker-compose.yml` to use different ports

### Database Connection Issues
- Ensure PostgreSQL container is running: `docker compose ps`
- Check logs: `docker compose logs postgres`
- Verify credentials in `.docker-secrets.txt`

## Manual Setup Alternative

If you prefer to set up services manually, see the [README.md](README.md) for manual installation instructions.

