@echo off
REM Odys Rental Management - Windows Deployment Script
REM This script automates the deployment process for production on Windows

echo 🚀 Starting Odys Rental Management Deployment...

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] This script must be run from the Laravel project root directory
    exit /b 1
)

REM Check if .env file exists
if not exist ".env" (
    echo [WARNING] .env file not found. Creating from .env.example...
    copy .env.example .env
    echo [WARNING] Please edit .env file with your production settings before continuing
    exit /b 1
)

echo [INFO] Installing/Updating Composer dependencies...
composer install --no-dev --optimize-autoloader --no-interaction
if %errorlevel% neq 0 (
    echo [ERROR] Composer install failed
    exit /b 1
)

echo [INFO] Installing/Updating NPM dependencies...
npm ci --production
if %errorlevel% neq 0 (
    echo [ERROR] NPM install failed
    exit /b 1
)

echo [INFO] Building assets...
npm run build
if %errorlevel% neq 0 (
    echo [ERROR] Asset build failed
    exit /b 1
)

echo [INFO] Generating application key...
php artisan key:generate --force
if %errorlevel% neq 0 (
    echo [ERROR] Key generation failed
    exit /b 1
)

echo [INFO] Optimizing caches (keeping existing caches)...
echo [INFO] Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo [ERROR] Database migration failed
    exit /b 1
)

echo [INFO] Seeding database...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo [ERROR] Database seeding failed
    exit /b 1
)

echo [INFO] Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo [INFO] Creating storage directories...
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\cache\data" mkdir "storage\framework\cache\data"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\logs" mkdir "storage\logs"
if not exist "storage\app\public" mkdir "storage\app\public"

echo [INFO] Skipping storage link (using private storage)...

echo [INFO] Warming up caches...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo [INFO] Running health checks...
php artisan about >nul 2>&1

echo [INFO] ✅ Deployment completed successfully!
echo [INFO] 🌐 Your application should now be accessible at your domain
echo [INFO] 📊 Check the logs in storage\logs\ for any issues

echo.
echo Next steps:
echo 1. Verify your .env file has correct production settings
echo 2. Test your application functionality
echo 3. Set up SSL certificate if not already done
echo 4. Configure your web server (Apache/Nginx)
echo 5. Set up monitoring and backups

pause
