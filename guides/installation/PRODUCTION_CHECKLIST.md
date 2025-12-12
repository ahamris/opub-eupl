# Production Deployment Checklist

Use this checklist to ensure your production deployment is complete and secure.

## Pre-Deployment

### Code Quality
- [ ] All tests passing (`php artisan test`)
- [ ] Code formatted with Pint (`vendor/bin/pint`)
- [ ] No debug code or console.log statements
- [ ] All environment variables documented in `.env.example`
- [ ] Dependencies up to date and secure

### Configuration
- [ ] `.env` file configured with production values
- [ ] `APP_ENV=production` set
- [ ] `APP_DEBUG=false` set
- [ ] `APP_KEY` generated and secure
- [ ] `APP_URL` set to production domain
- [ ] Database credentials configured
- [ ] Redis credentials configured
- [ ] Typesense API key set
- [ ] Log level set to `error` or `warning`

### Security
- [ ] Strong passwords for all services
- [ ] SSL/TLS certificates installed
- [ ] Security headers configured
- [ ] CSRF protection enabled
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] `.env` file not accessible via web
- [ ] Sensitive files excluded from version control

## Deployment

### Server Setup
- [ ] PHP 8.4+ installed with required extensions
- [ ] PostgreSQL 18+ installed and configured
- [ ] Redis 7+ installed and running
- [ ] Typesense 27.0+ installed and running
- [ ] Web server (Nginx/Apache) configured
- [ ] Queue worker configured (Supervisor/systemd)
- [ ] Cron job configured for scheduler

### Application Deployment
- [ ] Code deployed to server
- [ ] Dependencies installed (`composer install --no-dev --optimize-autoloader`)
- [ ] Assets built (`npm run build`)
- [ ] Configuration cached (`php artisan config:cache`)
- [ ] Routes cached (`php artisan route:cache`)
- [ ] Views cached (`php artisan view:cache`)
- [ ] Events cached (`php artisan event:cache`)
- [ ] Migrations run (`php artisan migrate --force`)
- [ ] Storage and cache directories have correct permissions

### Services
- [ ] Database connection working
- [ ] Redis connection working
- [ ] Typesense connection working
- [ ] Queue worker running
- [ ] Scheduler cron job active
- [ ] Web server serving application correctly

## Post-Deployment

### Verification
- [ ] Application accessible via HTTPS
- [ ] Health check endpoint responding (`/up`)
- [ ] Search functionality working
- [ ] API endpoints responding correctly
- [ ] No errors in logs
- [ ] Queue jobs processing
- [ ] Scheduled tasks running

### Monitoring
- [ ] Log monitoring set up
- [ ] Error tracking configured (if applicable)
- [ ] Performance monitoring active
- [ ] Uptime monitoring configured
- [ ] Alerting configured for critical issues

### Backup
- [ ] Database backup strategy in place
- [ ] Application backup strategy in place
- [ ] Backup restoration tested
- [ ] Backup schedule configured

### Documentation
- [ ] Deployment process documented
- [ ] Environment variables documented
- [ ] Troubleshooting guide available
- [ ] Rollback procedure documented

## Security Hardening

### Server Security
- [ ] Firewall configured
- [ ] SSH key authentication only (no passwords)
- [ ] Unnecessary services disabled
- [ ] Regular security updates scheduled
- [ ] Fail2ban or similar installed

### Application Security
- [ ] Rate limiting configured
- [ ] Input validation in place
- [ ] SQL injection prevention verified
- [ ] XSS protection verified
- [ ] CSRF tokens working
- [ ] Secure session configuration

### Data Protection
- [ ] Database backups encrypted
- [ ] Sensitive data encrypted at rest
- [ ] API keys stored securely
- [ ] Password hashing using bcrypt/argon2

## Performance Optimization

### Application
- [ ] Opcache enabled and configured
- [ ] Redis caching enabled
- [ ] Query optimization verified
- [ ] Asset compression enabled
- [ ] CDN configured (if applicable)

### Database
- [ ] Indexes created and optimized
- [ ] Connection pooling configured
- [ ] Query caching enabled
- [ ] Regular VACUUM scheduled

### Server
- [ ] PHP-FPM optimized
- [ ] Web server optimized
- [ ] Memory limits appropriate
- [ ] CPU limits configured (if applicable)

## Maintenance

### Regular Tasks
- [ ] Monitor error logs daily
- [ ] Review performance metrics weekly
- [ ] Check disk space weekly
- [ ] Review security logs weekly
- [ ] Update dependencies monthly
- [ ] Test backup restoration quarterly

### Updates
- [ ] PHP updates planned
- [ ] Laravel updates planned
- [ ] Dependency updates scheduled
- [ ] Security patches applied promptly

## Emergency Procedures

### Rollback
- [ ] Rollback procedure documented
- [ ] Previous version tagged in Git
- [ ] Database rollback procedure tested
- [ ] Rollback script prepared

### Incident Response
- [ ] Contact information documented
- [ ] Escalation procedure defined
- [ ] Incident response plan ready
- [ ] Communication plan prepared

## Compliance

### Legal
- [ ] Privacy policy in place
- [ ] Terms of service in place
- [ ] GDPR compliance verified (if applicable)
- [ ] Data retention policy defined

### Accessibility
- [ ] WCAG compliance verified
- [ ] Accessibility testing completed
- [ ] Screen reader compatibility tested

---

**Last Updated**: 2024-12-12  
**Version**: 1.0
