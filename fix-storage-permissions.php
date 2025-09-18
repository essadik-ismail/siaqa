<?php

/**
 * Fix Storage Permissions Script
 * This script ensures all necessary storage directories exist and have proper permissions
 */

$directories = [
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

echo "Creating storage directories and setting permissions...\n";

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        if (mkdir($directory, 0755, true)) {
            echo "✓ Created directory: $directory\n";
        } else {
            echo "✗ Failed to create directory: $directory\n";
        }
    } else {
        echo "✓ Directory already exists: $directory\n";
    }
    
    // Set permissions
    if (is_dir($directory)) {
        if (chmod($directory, 0755)) {
            echo "✓ Set permissions for: $directory\n";
        } else {
            echo "✗ Failed to set permissions for: $directory\n";
        }
    }
}

// Create .gitignore files for storage directories
$gitignoreContent = "*\n!.gitignore\n";

$gitignoreDirs = [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/testing',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($gitignoreDirs as $dir) {
    $gitignoreFile = $dir . '/.gitignore';
    if (!file_exists($gitignoreFile)) {
        if (file_put_contents($gitignoreFile, $gitignoreContent)) {
            echo "✓ Created .gitignore for: $dir\n";
        } else {
            echo "✗ Failed to create .gitignore for: $dir\n";
        }
    }
}

echo "\nStorage permissions fix completed!\n";
echo "If you're still having issues, check:\n";
echo "1. Web server user has write permissions to storage directories\n";
echo "2. SELinux is not blocking file operations (if applicable)\n";
echo "3. Disk space is available\n";
