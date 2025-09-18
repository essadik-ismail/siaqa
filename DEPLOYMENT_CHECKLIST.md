# 🚀 Odys Rental Management - Deployment Checklist

## Pre-Deployment Checklist

### ✅ Environment Setup
- [ ] Copy `env.production.example` to `.env`
- [ ] Update database credentials in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Configure mail settings
- [ ] Set up SSL certificate
- [ ] Configure domain name

### ✅ Server Requirements
- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] MySQL 8.0+ installed
- [ ] Apache/Nginx configured
- [ ] SSL certificate installed
- [ ] File permissions set correctly

### ✅ Database Setup
- [ ] Create production database
- [ ] Create database user with proper permissions
- [ ] Test database connection
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed initial data if needed

### ✅ File Permissions
- [ ] `storage/` directory writable (755)
- [ ] `bootstrap/cache/` directory writable (755)
- [ ] `storage/framework/sessions/` directory exists and writable
- [ ] `storage/app/images/` directory exists and writable
- [ ] Web server can read all files

### ✅ Security Configuration
- [ ] `.env` file not accessible via web
- [ ] Sensitive files blocked in `.htaccess`
- [ ] HTTPS enforced
- [ ] Security headers configured
- [ ] CSP (Content Security Policy) configured
- [ ] Rate limiting enabled

### ✅ Performance Optimization
- [ ] OPcache enabled
- [ ] Gzip compression enabled
- [ ] Browser caching configured
- [ ] Static assets optimized
- [ ] Database queries optimized
- [ ] Cache system configured

## Deployment Steps

### 1. Upload Files
```bash
# Upload all files to production server
# Exclude: node_modules, .git, storage/logs, .env
```

### 2. Run Deployment Script
```bash
# Linux/Mac
chmod +x deploy-production.sh
./deploy-production.sh

# Windows
deploy-production.bat
```

### 3. Manual Steps (if needed)
```bash
# Create storage directories
php fix-storage-directories.php

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Generate key if needed
php artisan key:generate --force
```

### 4. Configure Web Server

#### Apache (.htaccess)
```bash
# Copy production .htaccess
cp public/.htaccess.production public/.htaccess
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name odys.ma www.odys.ma;
    
    root /path/to/rental/public;
    index index.php;
    
    # SSL configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
    
    # Cache static assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Block sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }
    
    location ~ /(storage|bootstrap/cache|vendor) {
        deny all;
    }
}
```

### 5. Post-Deployment Verification

#### Health Checks
- [ ] Visit `/health` - should return 200 status
- [ ] Visit `/health/simple` - should return 200 status
- [ ] Check all health check components

#### Functionality Tests
- [ ] Homepage loads correctly
- [ ] User registration works
- [ ] User login works
- [ ] Dashboard loads
- [ ] Vehicle management works
- [ ] Reservation system works
- [ ] Image upload works (private storage)
- [ ] All forms submit correctly

#### Security Tests
- [ ] Sensitive files not accessible
- [ ] HTTPS redirects work
- [ ] Security headers present
- [ ] CSP blocks unauthorized resources
- [ ] Rate limiting works

#### Performance Tests
- [ ] Page load times < 3 seconds
- [ ] Images load via signed URLs
- [ ] Caching works correctly
- [ ] Database queries optimized

## Monitoring Setup

### 1. Log Monitoring
```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Monitor web server logs
tail -f /var/log/apache2/access.log
tail -f /var/log/apache2/error.log
```

### 2. Health Monitoring
- Set up monitoring for `/health` endpoint
- Monitor database connectivity
- Monitor disk space usage
- Monitor memory usage
- Set up alerts for failures

### 3. Backup Strategy
```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz storage/ public/
```

## Troubleshooting

### Common Issues

#### Session Storage Error
```bash
# Create sessions directory
mkdir -p storage/framework/sessions
chmod 755 storage/framework/sessions
```

#### Permission Errors
```bash
# Fix ownership
chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache
```

#### Database Connection Error
- Check database credentials in `.env`
- Verify database server is running
- Check firewall settings
- Test connection manually

#### Image Upload Issues
- Check `storage/app/images` directory exists
- Verify permissions on image directory
- Check file upload limits in PHP
- Verify private image serving works

### Emergency Procedures

#### Rollback
```bash
# Restore from backup
cp backups/latest/.env .env
php artisan migrate:rollback
# Restore files from backup
```

#### Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up
```

## Success Criteria

- [ ] Application loads without errors
- [ ] All functionality works as expected
- [ ] Health checks pass
- [ ] Security measures in place
- [ ] Performance meets requirements
- [ ] Monitoring configured
- [ ] Backup strategy implemented

## Support

For deployment issues:
1. Check logs: `storage/logs/laravel.log`
2. Verify health: `/health`
3. Check file permissions
4. Verify database connection
5. Review web server configuration

---

**Deployment Date:** _______________
**Deployed By:** _______________
**Version:** 1.0.0
**Environment:** Production