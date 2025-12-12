#!/bin/bash

# Production Deployment Script for Open Overheid Platform
# This script handles production deployment with optimizations

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_info() {
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

# Check if running in production environment
if [ "${APP_ENV}" != "production" ]; then
    print_warning "APP_ENV is not set to 'production'. Some optimizations may be skipped."
fi

print_info "Starting production deployment..."

# 1. Install/Update Dependencies
print_info "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

print_info "Installing Node dependencies..."
npm ci --production

# 2. Build Assets
print_info "Building production assets..."
npm run build

# 3. Optimize Laravel
print_info "Optimizing Laravel for production..."

# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Clear and cache events
php artisan event:clear
php artisan event:cache

# Optimize autoloader
composer dump-autoload --optimize --classmap-authoritative

# 4. Run Database Migrations
print_info "Running database migrations..."
php artisan migrate --force

# 5. Clear Application Cache
print_info "Clearing application cache..."
php artisan cache:clear

# 6. Set Permissions
print_info "Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || print_warning "Could not set ownership (may require sudo)"

# 7. Health Check
print_info "Running health check..."
if php artisan about > /dev/null 2>&1; then
    print_success "Application health check passed"
else
    print_error "Application health check failed"
    exit 1
fi

print_success "Production deployment completed successfully!"
print_info "Next steps:"
echo "  1. Ensure queue worker is running: php artisan queue:work --daemon"
echo "  2. Set up cron for scheduler: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1"
echo "  3. Configure your web server (Nginx/Apache)"
echo "  4. Set up SSL/TLS certificates"
echo "  5. Configure monitoring and logging"
