#!/bin/bash

# 🚀 Odys Rental Management - Production Deployment Script
# This script prepares and deploys the application to production

set -e  # Exit on any error

echo "🚀 Starting Odys Rental Management Production Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="Odys Rental Management"
APP_ENV="production"
BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"

# Functions
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    log_error "Please do not run this script as root"
    exit 1
fi

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    log_error "Please run this script from the Laravel project root directory"
    exit 1
fi

log_info "Deploying $APP_NAME to $APP_ENV environment"

# Step 1: Create backup
log_info "Creating backup..."
mkdir -p $BACKUP_DIR
if [ -f ".env" ]; then
    cp .env $BACKUP_DIR/
    log_success "Environment file backed up"
fi

# Step 2: Install/Update dependencies
log_info "Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
log_success "Dependencies installed"

# Step 3: Create storage directories
log_info "Creating storage directories..."
php fix-storage-directories.php
log_success "Storage directories created"

# Step 4: Set proper permissions
log_info "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/app/images
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || log_warning "Could not set ownership (run as root if needed)"
log_success "Permissions set"

# Step 5: Optimize configurations (keeping existing caches)
log_info "Optimizing application..."

# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
log_success "Application optimized"

# Step 6: Run migrations
log_info "Running database migrations..."
php artisan migrate --force
log_success "Database migrations completed"

# Step 7: Generate application key if needed
if [ -z "$(grep APP_KEY .env 2>/dev/null | cut -d '=' -f2)" ]; then
    log_info "Generating application key..."
    php artisan key:generate --force
    log_success "Application key generated"
fi

# Step 8: Skip storage link (using private storage)
log_info "Skipping storage link (using private storage)..."

# Step 9: Optimize Composer autoloader
log_info "Optimizing Composer autoloader..."
composer dump-autoload --optimize --no-dev

# Step 10: Health check
log_info "Running health check..."
if php artisan route:list | grep -q "health"; then
    log_success "Health check endpoint available"
else
    log_warning "Health check endpoint not found"
fi

# Step 11: Security checks
log_info "Running security checks..."

# Check for debug mode
if grep -q "APP_DEBUG=true" .env 2>/dev/null; then
    log_warning "APP_DEBUG is set to true in production!"
fi

# Check for proper environment
if ! grep -q "APP_ENV=production" .env 2>/dev/null; then
    log_warning "APP_ENV is not set to production!"
fi

log_success "Security checks completed"

# Step 12: Final optimizations
log_info "Running final optimizations..."

# Optimize without clearing existing caches
php artisan optimize

# Step 13: Display deployment summary
echo ""
log_success "🎉 Deployment completed successfully!"
echo ""
echo "📋 Deployment Summary:"
echo "======================"
echo "• Application: $APP_NAME"
echo "• Environment: $APP_ENV"
echo "• Backup created: $BACKUP_DIR"
echo "• Storage: Private (no public symlink)"
echo "• Images: Secured with signed URLs"
echo "• Database: Migrated"
echo "• Cache: Optimized"
echo ""
echo "🔧 Next Steps:"
echo "=============="
echo "1. Verify your .env file configuration"
echo "2. Test the application functionality"
echo "3. Check health endpoint: /health"
echo "4. Monitor logs: storage/logs/laravel.log"
echo ""
echo "🔒 Security Notes:"
echo "=================="
echo "• All images are stored privately"
echo "• No public storage symlink created"
echo "• Images accessible only via signed URLs"
echo "• Session storage is file-based"
echo ""
log_success "Deployment script completed!"
