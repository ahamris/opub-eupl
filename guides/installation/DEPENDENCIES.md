# Open Overheid Platform - Complete Dependency List

## System Requirements

### Operating System
- Linux (Ubuntu, Debian, Fedora, RHEL, CentOS, Arch)
- macOS (via Docker Desktop)
- Windows (via WSL2 or Docker Desktop)

### Architecture
- amd64 (x86_64)
- arm64
- armv7

## Core Dependencies

### 1. Docker & Container Runtime
- **Docker**: 20.10+ (required)
- **Docker Compose**: V2 (included with Docker)
- **Podman**: Alternative to Docker (optional)

### 2. Node.js & npm
- **Node.js**: v25.2.1 (Current) - https://nodejs.org/en/download/current
- **npm**: Included with Node.js (v10.x+)
- **Purpose**: Build frontend assets (Vite, Tailwind CSS)

### 3. PHP & Composer
- **PHP**: 8.2+ (required)
- **Composer**: Latest (PHP dependency manager)
- **Purpose**: Laravel framework and PHP packages

### 4. Database
- **PostgreSQL**: 18 (latest) - via Docker
- **Purpose**: Primary database for documents

### 5. Search Engine
- **Typesense**: Latest - via Docker
- **Purpose**: Full-text search engine

## Application Dependencies

### PHP Dependencies (composer.json)
```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "typesense/typesense-php": "^5.2"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pail": "^1.2.2",
    "laravel/pint": "^1.24",
    "laravel/sail": "^1.41",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "pestphp/pest": "^4.1",
    "pestphp/pest-plugin-laravel": "^4.0"
  }
}
```

### Node.js Dependencies (package.json)
```json
{
  "devDependencies": {
    "@tailwindcss/vite": "^4.1.17",
    "axios": "^1.11.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^2.0.0",
    "tailwindcss": "^4.1.17",
    "vite": "^7.0.7"
  }
}
```

### Frontend Libraries
- **Alpine.js**: 3.x (via CDN)
- **Font Awesome**: 6.5.2 (local)
- **Inter Font**: Google Fonts
- **JetBrains Mono**: Google Fonts

## Docker Services

### 1. PostgreSQL 18
- **Image**: `postgres:18-alpine`
- **Port**: 5432
- **Purpose**: Main database

### 2. pgAdmin 4
- **Image**: `dpage/pgadmin4:latest`
- **Port**: 5050
- **Purpose**: Database management UI

### 3. Typesense
- **Image**: `typesense/typesense:latest`
- **Port**: 8108
- **Purpose**: Search engine

### 4. Dockge (NEW)
- **Image**: `louislam/dockge:latest`
- **Port**: 5001 (default)
- **Purpose**: Docker Compose stack manager
- **Repository**: https://github.com/louislam/dockge

## Build Tools

### Frontend
- **Vite**: ^7.0.7 - Build tool
- **Tailwind CSS**: ^4.1.17 - CSS framework
- **Laravel Vite Plugin**: ^2.0.0 - Laravel integration

### Backend
- **Laravel Framework**: ^12.0 - PHP framework
- **Composer**: PHP package manager

## Development Tools

- **Pest**: ^4.1 - Testing framework
- **Laravel Pint**: ^1.24 - Code formatter
- **Laravel Pail**: ^1.2.2 - Log viewer
- **Concurrently**: ^9.0.1 - Run multiple commands

## Version Summary

| Component | Version | Source |
|-----------|---------|--------|
| Node.js | v25.2.1 (Current) | https://nodejs.org/en/download/current |
| npm | v10.x+ (bundled) | Included with Node.js |
| PHP | 8.2+ | System/package manager |
| Composer | Latest | https://getcomposer.org |
| Docker | 20.10+ | https://docs.docker.com |
| PostgreSQL | 18 | Docker image |
| Typesense | Latest | Docker image |
| Dockge | Latest | Docker image |
| Laravel | 12.0 | Composer |
| Vite | 7.0.7 | npm |
| Tailwind CSS | 4.1.17 | npm |

## Installation Order

1. **System Dependencies**
   - Docker & Docker Compose
   - Node.js & npm
   - PHP & Composer

2. **Docker Services**
   - PostgreSQL
   - pgAdmin
   - Typesense
   - Dockge

3. **Application Setup**
   - Composer install (PHP dependencies)
   - npm install (Node dependencies)
   - npm run build (Build assets)
   - Database migrations

---

**Last Updated**: 2025-01-XX
**Maintained By**: Open Overheid Platform Team

