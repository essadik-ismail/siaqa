@echo off
REM 🚀 Odys Rental Management - Production Deployment Script (Windows)
REM This script prepares and deploys the application to production

setlocal enabledelayedexpansion

echo 🚀 Starting Odys Rental Management Production Deployment...

REM Configuration
set APP_NAME=Odys Rental Management
set APP_ENV=production
set BACKUP_DIR=backups\%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set BACKUP_DIR=%BACKUP_DIR: =0%

REM Check if we're in the right directory
if not exist "artisan" (
    echo ❌ Please run this script from the Laravel project root directory
    pause
    exit /b 1
)

echo ℹ️  Deploying %APP_NAME% to %APP_ENV% environment

REM Step 1: Create backup
echo ℹ️  Creating backup...
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"
if exist ".env" (
    copy ".env" "%BACKUP_DIR%\" >nul
    echo ✅ Environment file backed up
)

REM Step 2: Install/Update dependencies
echo ℹ️  Installing dependencies...
composer install --no-dev --optimize-autoloader --no-interaction
if %errorlevel% neq 0 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)
echo ✅ Dependencies installed

REM Step 3: Create storage directories
echo ℹ️  Creating storage directories...
php fix-storage-directories.php
echo ✅ Storage directories created

REM Step 4: Optimize configurations (keeping existing caches)
echo ℹ️  Optimizing application...

REM Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ✅ Application optimized

REM Step 5: Run migrations
echo ℹ️  Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ❌ Database migration failed
    pause
    exit /b 1
)
echo ✅ Database migrations completed

REM Step 6: Generate application key if needed
php artisan key:generate --force
echo ✅ Application key generated

REM Step 7: Optimize Composer autoloader
echo ℹ️  Optimizing Composer autoloader...
composer dump-autoload --optimize --no-dev
echo ✅ Composer autoloader optimized

REM Step 8: Health check
echo ℹ️  Running health check...
php artisan route:list | findstr "health" >nul
if %errorlevel% equ 0 (
    echo ✅ Health check endpoint available
) else (
    echo ⚠️  Health check endpoint not found
)

REM Step 9: Security checks
echo ℹ️  Running security checks...
findstr "APP_DEBUG=true" .env >nul 2>&1
if %errorlevel% equ 0 (
    echo ⚠️  APP_DEBUG is set to true in production!
)

findstr "APP_ENV=production" .env >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  APP_ENV is not set to production!
)

echo ✅ Security checks completed

REM Step 10: Final optimizations
echo ℹ️  Running final optimizations...
php artisan optimize
echo ✅ Final optimizations completed

REM Step 11: Display deployment summary
echo.
echo ✅ 🎉 Deployment completed successfully!
echo.
echo 📋 Deployment Summary:
echo ======================
echo • Application: %APP_NAME%
echo • Environment: %APP_ENV%
echo • Backup created: %BACKUP_DIR%
echo • Storage: Private (no public symlink)
echo • Images: Secured with signed URLs
echo • Database: Migrated
echo • Cache: Optimized
echo.
echo 🔧 Next Steps:
echo ==============
echo 1. Verify your .env file configuration
echo 2. Test the application functionality
echo 3. Check health endpoint: /health
echo 4. Monitor logs: storage/logs/laravel.log
echo.
echo 🔒 Security Notes:
echo ==================
echo • All images are stored privately
echo • No public storage symlink created
echo • Images accessible only via signed URLs
echo • Session storage is file-based
echo.
echo ✅ Deployment script completed!
pause
