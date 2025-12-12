# Production Deployment Guide

This guide covers deploying the Open Overheid Platform to a production environment.

## Prerequisites

- PHP 8.4+ with required extensions
- PostgreSQL 18+
- Redis 7+
- Typesense 27.0+
- Node.js 25.2.1+ and npm
- Composer
- Nginx or Apache web server
- SSL/TLS certificate (recommended)

## Quick Deployment

### Using the Deployment Script

```bash
chmod +x deploy.sh
APP_ENV=production ./deploy.sh
```

### Manual Deployment Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd oo
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci --production
   ```

3. **Build assets**
   ```bash
   npm run build
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your production settings
   php artisan key:generate
   ```

5. **Optimize Laravel**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

6. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

7. **Set permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

## Docker Production Deployment

### Using Docker Compose

1. **Copy production docker-compose file**
   ```bash
   cp docker-compose.prod.yml docker-compose.yml
   ```

2. **Set environment variables**
   ```bash
   export POSTGRES_USER=your_user
   export POSTGRES_PASSWORD=your_password
   export POSTGRES_DB=open_overheid
   export TYPESENSE_API_KEY=your_api_key
   ```

3. **Start services**
   ```bash
   docker compose up -d
   ```

4. **Deploy application**
   ```bash
   ./deploy.sh
   ```

## Web Server Configuration

### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    root /path/to/oo/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache Configuration

The `.htaccess` file in the `public` directory is already configured with:
- Security headers
- Compression
- Browser caching
- URL rewriting

Ensure `mod_rewrite` is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Environment Configuration

### Required Environment Variables

See `.env.example` for all available options. Key production settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use Redis for cache and sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=open_overheid
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Typesense
TYPESENSE_SYNC_ENABLED=true
TYPESENSE_API_KEY=your_api_key
TYPESENSE_HOST=localhost
TYPESENSE_PORT=8108
```

## Queue Worker Setup

### Using Supervisor (Recommended)

Create `/etc/supervisor/conf.d/openoverheid-worker.conf`:

```ini
[program:openoverheid-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/oo/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/oo/storage/logs/worker.log
stopwaitsecs=3600
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start openoverheid-worker:*
```

### Using systemd

Create `/etc/systemd/system/openoverheid-worker.service`:

```ini
[Unit]
Description=Open Overheid Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/oo/artisan queue:work redis --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

Then:
```bash
sudo systemctl daemon-reload
sudo systemctl enable openoverheid-worker
sudo systemctl start openoverheid-worker
```

## Scheduled Tasks

Add to crontab (`crontab -e`):

```bash
* * * * * cd /path/to/oo && php artisan schedule:run >> /dev/null 2>&1
```

Or use systemd timer (recommended for production).

## Monitoring

### Laravel Telescope (Development)
- Only enable in development/staging
- Access at `/telescope` (if enabled)

### Laravel Pulse (Production)
```bash
composer require laravel/pulse
php artisan pulse:install
php artisan migrate
```

### Log Monitoring
- Logs are stored in `storage/logs/`
- Use daily log rotation (configured in `config/logging.php`)
- Consider using external log aggregation (e.g., Papertrail, Loggly)

## Performance Optimization

### Opcache Configuration

Add to `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

### Database Optimization

PostgreSQL configuration (in `docker-compose.prod.yml` or `postgresql.conf`):
- `shared_buffers`: 25% of RAM
- `effective_cache_size`: 50-75% of RAM
- `maintenance_work_mem`: 1-2GB
- `checkpoint_completion_target`: 0.9

### Redis Optimization

Redis configuration (in `docker-compose.prod.yml`):
- `maxmemory`: 512mb (adjust based on available RAM)
- `maxmemory-policy`: allkeys-lru
- Enable persistence with AOF

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secured
- [ ] Redis password set (if exposed)
- [ ] Typesense API key is strong
- [ ] SSL/TLS certificates installed
- [ ] Security headers configured
- [ ] File permissions set correctly
- [ ] `.env` file not accessible via web
- [ ] Regular security updates
- [ ] Firewall configured
- [ ] Backup strategy in place

## Backup Strategy

### Database Backup

```bash
# Daily backup script
pg_dump -U username -d open_overheid > backup_$(date +%Y%m%d).sql
```

### Application Backup

```bash
# Backup storage and .env
tar -czf app_backup_$(date +%Y%m%d).tar.gz storage/ .env
```

### Automated Backups

Use cron or a backup service:
```bash
0 2 * * * /path/to/backup-script.sh
```

## Troubleshooting

### Clear All Caches
```bash
php artisan optimize:clear
```

### Check Queue Status
```bash
php artisan queue:monitor
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Test Database Connection
```bash
php artisan db:show
```

### Test Redis Connection
```bash
php artisan tinker
>>> Redis::ping()
```

## Rollback Procedure

1. **Restore previous code version**
   ```bash
   git checkout <previous-commit>
   ```

2. **Clear caches**
   ```bash
   php artisan optimize:clear
   ```

3. **Restore database** (if needed)
   ```bash
   psql -U username -d open_overheid < backup.sql
   ```

4. **Restart services**
   ```bash
   sudo systemctl restart php8.4-fpm
   sudo systemctl restart nginx
   ```

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review [README.md](README.md)
3. Check [guides/](guides/) directory
