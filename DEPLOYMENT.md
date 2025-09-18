# 🚀 Odys Rental Management - Deployment Guide

This guide will help you deploy the Odys Rental Management system to a production server.

## 📋 Prerequisites

### Server Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.3+)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Composer**: Latest version
- **Node.js**: 16+ and NPM
- **SSL Certificate**: For HTTPS (recommended)

### PHP Extensions Required
```bash
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-mysql
php-pdo
php-tokenizer
php-fileinfo
php-bcmath
php-json
```

## 🔧 Server Setup

### 1. Install Required Software

#### Ubuntu/Debian:
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP and extensions
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-json -y

# Install MySQL
sudo apt install mysql-server -y

# Install Apache
sudo apt install apache2 -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y
```

#### CentOS/RHEL:
```bash
# Install EPEL repository
sudo yum install epel-release -y

# Install PHP and extensions
sudo yum install php82 php82-cli php82-fpm php82-mysqlnd php82-xml php82-mbstring php82-curl php82-zip php82-gd php82-bcmath php82-json -y

# Install MySQL
sudo yum install mysql-server -y

# Install Apache
sudo yum install httpd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install nodejs -y
```

### 2. Configure MySQL

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE odys_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'odys_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON odys_rental.* TO 'odys_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Configure Apache

#### Create Virtual Host
```bash
sudo nano /etc/apache2/sites-available/odys.ma.conf
```

```apache
<VirtualHost *:80>
    ServerName odys.ma
    ServerAlias www.odys.ma
    DocumentRoot /var/www/odys/public
    
    <Directory /var/www/odys/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/odys_error.log
    CustomLog ${APACHE_LOG_DIR}/odys_access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName odys.ma
    ServerAlias www.odys.ma
    DocumentRoot /var/www/odys/public
    
    <Directory /var/www/odys/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
    
    ErrorLog ${APACHE_LOG_DIR}/odys_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/odys_ssl_access.log combined
</VirtualHost>
```

#### Enable Site and Modules
```bash
# Enable required Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod deflate

# Enable the site
sudo a2ensite odys.ma.conf

# Disable default site
sudo a2dissite 000-default.conf

# Restart Apache
sudo systemctl restart apache2
```

## 📦 Application Deployment

### 1. Clone and Setup

```bash
# Create application directory
sudo mkdir -p /var/www/odys
sudo chown -R $USER:$USER /var/www/odys

# Clone repository (replace with your actual repository)
git clone https://github.com/yourusername/odys-rental.git /var/www/odys
cd /var/www/odys

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

#### Key Environment Variables:
```env
APP_NAME="Odys Rental Management"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://odys.ma

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=odys_rental
DB_USERNAME=odys_user
DB_PASSWORD=your_secure_password

SESSION_DRIVER=file
CACHE_STORE=file

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@odys.ma"
MAIL_FROM_NAME="Odys Rental Management"
```

### 3. Run Deployment Script

#### Linux/Mac:
```bash
chmod +x deploy.sh
./deploy.sh
```

#### Windows:
```cmd
deploy.bat
```

### 4. Set Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/odys

# Set proper permissions
sudo chmod -R 755 /var/www/odys
sudo chmod -R 775 /var/www/odys/storage
sudo chmod -R 775 /var/www/odys/bootstrap/cache
```

## 🔒 Security Configuration

### 1. Firewall Setup

```bash
# Enable UFW firewall
sudo ufw enable

# Allow SSH
sudo ufw allow ssh

# Allow HTTP and HTTPS
sudo ufw allow 80
sudo ufw allow 443

# Check status
sudo ufw status
```

### 2. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Obtain SSL certificate
sudo certbot --apache -d odys.ma -d www.odys.ma

# Test auto-renewal
sudo certbot renew --dry-run
```

### 3. Additional Security

```bash
# Disable PHP execution in uploads
echo "php_flag engine off" | sudo tee /var/www/odys/public/uploads/.htaccess

# Set secure file permissions
sudo find /var/www/odys -type f -exec chmod 644 {} \;
sudo find /var/www/odys -type d -exec chmod 755 {} \;
```

## 📊 Monitoring and Maintenance

### 1. Log Monitoring

```bash
# Monitor Laravel logs
tail -f /var/www/odys/storage/logs/laravel.log

# Monitor Apache logs
tail -f /var/log/apache2/odys_error.log
tail -f /var/log/apache2/odys_access.log
```

### 2. Database Backups

Create a backup script:

```bash
sudo nano /usr/local/bin/backup-odys.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/odys"
DB_NAME="odys_rental"
DB_USER="odys_user"
DB_PASS="your_secure_password"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/odys_db_$DATE.sql

# Application backup
tar -czf $BACKUP_DIR/odys_app_$DATE.tar.gz /var/www/odys

# Keep only last 7 days of backups
find $BACKUP_DIR -name "odys_*" -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
chmod +x /usr/local/bin/backup-odys.sh

# Add to crontab for daily backups
crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-odys.sh
```

### 3. Performance Optimization

```bash
# Install Redis for better performance
sudo apt install redis-server -y

# Update .env to use Redis
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🚨 Troubleshooting

### Common Issues:

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/odys
   sudo chmod -R 775 /var/www/odys/storage
   sudo chmod -R 775 /var/www/odys/bootstrap/cache
   ```

2. **Database Connection Issues**
   - Check database credentials in `.env`
   - Verify MySQL service is running
   - Test connection: `mysql -u odys_user -p odys_rental`

3. **Session Issues**
   - Ensure `storage/framework/sessions` exists and is writable
   - Check session driver in `.env`

4. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

5. **Asset Issues**
   ```bash
   npm run build
   # Note: Storage link not needed for private storage deployment
   ```

## 📈 Performance Tips

1. **Enable OPcache** in PHP configuration
2. **Use Redis** for caching and sessions
3. **Enable Gzip compression** in Apache
4. **Set up CDN** for static assets
5. **Monitor server resources** regularly

## 🔄 Updates and Maintenance

### Regular Maintenance Tasks:

1. **Weekly:**
   - Check application logs
   - Monitor server resources
   - Test backup restoration

2. **Monthly:**
   - Update dependencies
   - Review security logs
   - Performance optimization

3. **Quarterly:**
   - Security audit
   - Database optimization
   - Full system backup

## 📞 Support

For deployment issues or questions:
- Check the logs in `storage/logs/`
- Review this documentation
- Contact your system administrator

---

**Note**: This deployment guide assumes a standard LAMP stack setup. Adjust configurations based on your specific server environment and requirements.
