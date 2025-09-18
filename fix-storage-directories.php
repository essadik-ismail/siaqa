<?php
/**
 * Fix Storage Directories Script
 * Creates all necessary storage directories with proper permissions
 */

$directories = [
    'storage/app',
    'storage/app/images',
    'storage/app/private',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

echo "Creating storage directories...\n";

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created: $dir\n";
        } else {
            echo "❌ Failed to create: $dir\n";
        }
    } else {
        echo "✅ Exists: $dir\n";
    }
    
    // Set permissions
    if (is_dir($dir)) {
        chmod($dir, 0755);
    }
}

echo "\nStorage directories setup complete!\n";
echo "✅ Private image storage configured\n";
echo "✅ Session storage fixed\n";
echo "✅ No public symlink needed\n";
echo "\nNext steps:\n";
echo "1. Run: php artisan config:clear\n";
echo "2. Run: php artisan route:clear\n";
echo "3. Update your views to use private images\n";
echo "4. Deploy to production\n";
