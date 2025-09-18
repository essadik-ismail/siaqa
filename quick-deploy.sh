#!/bin/bash

# 🚀 Quick Deploy Script for Odys Rental Management
# Use this for quick deployments after initial setup

echo "🚀 Quick Deploy - Odys Rental Management"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Please run this script from the Laravel project root directory"
    exit 1
fi

# Quick deployment steps
echo "ℹ️  Running quick deployment..."

# 1. Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Create storage directories
echo "📁 Creating storage directories..."
php fix-storage-directories.php

# 3. Optimize (keeping existing caches)
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# 6. Final optimization
echo "🔧 Final optimizations..."
php artisan optimize

echo "✅ Quick deployment completed!"
echo "🔍 Check health: /health"
echo "📊 Monitor logs: storage/logs/laravel.log"
