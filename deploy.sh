#!/bin/bash

# Odys Rental Management - Deployment Script
# This script automates the deployment process for production

set -e

echo "🚀 Starting Odys Rental Management Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    print_warning ".env file not found. Creating from .env.example..."
    cp .env.example .env
    print_warning "Please edit .env file with your production settings before continuing"
    exit 1
fi

print_status "Installing/Updating Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

print_status "Installing/Updating NPM dependencies..."
npm ci --production

print_status "Building assets..."
npm run build

print_status "Generating application key..."
php artisan key:generate --force

print_status "Optimizing caches (keeping existing caches)..."
print_status "Running database migrations..."
php artisan migrate --force

print_status "Seeding database..."
php artisan db:seed --force

print_status "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

print_status "Creating storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public

print_status "Skipping storage link (using private storage)..."

print_status "Warming up caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Running health checks..."
php artisan about >/dev/null 2>&1

print_status "✅ Deployment completed successfully!"
print_status "🌐 Your application should now be accessible at your domain"
print_status "📊 Check the logs in storage/logs/ for any issues"

echo ""
echo "Next steps:"
echo "1. Verify your .env file has correct production settings"
echo "2. Test your application functionality"
echo "3. Set up SSL certificate if not already done"
echo "4. Configure your web server (Apache/Nginx)"
echo "5. Set up monitoring and backups"
