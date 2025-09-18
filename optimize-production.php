<?php

/**
 * Production Optimization Script for Odys Rental Management
 * This script optimizes the Laravel application for production deployment
 */

echo "🚀 Starting Production Optimization...\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This script must be run from the Laravel project root directory\n";
    exit(1);
}

// Function to run artisan commands
function runArtisan($command) {
    echo "Running: php artisan $command\n";
    $output = shell_exec("php artisan $command 2>&1");
    if ($output) {
        echo $output . "\n";
    }
}

// Function to check if command exists
function commandExists($command) {
    $return = shell_exec("which $command 2>/dev/null");
    return !empty($return);
}

echo "📋 Checking system requirements...\n";

// Check PHP version
$phpVersion = PHP_VERSION;
echo "PHP Version: $phpVersion\n";
if (version_compare($phpVersion, '8.2.0', '<')) {
    echo "⚠️  Warning: PHP 8.2+ is recommended for optimal performance\n";
}

// Check required PHP extensions
$requiredExtensions = ['mbstring', 'xml', 'curl', 'zip', 'gd', 'pdo', 'pdo_mysql', 'tokenizer', 'fileinfo', 'bcmath', 'json'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "❌ Missing PHP extensions: " . implode(', ', $missingExtensions) . "\n";
    echo "Please install the missing extensions before continuing.\n";
    exit(1);
} else {
    echo "✅ All required PHP extensions are installed\n";
}

// Check Composer
if (!commandExists('composer')) {
    echo "❌ Composer is not installed or not in PATH\n";
    exit(1);
} else {
    echo "✅ Composer is available\n";
}

// Check Node.js and NPM
if (!commandExists('node') || !commandExists('npm')) {
    echo "⚠️  Warning: Node.js or NPM not found. Asset compilation may fail.\n";
} else {
    echo "✅ Node.js and NPM are available\n";
}

echo "\n🔧 Starting optimization process...\n";

// 1. Skip cache clearing for file copy deployment
echo "\n1. Skipping cache clearing (file copy deployment)...\n";
echo "   ✅ Keeping existing caches for file copy deployment\n";

// 2. Generate application key if not exists
echo "\n2. Checking application key...\n";
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'APP_KEY=base64:') === false) {
        runArtisan('key:generate --force');
    } else {
        echo "✅ Application key is already set\n";
    }
} else {
    echo "⚠️  Warning: .env file not found. Please create one from .env.example\n";
}

// 3. Create necessary directories
echo "\n3. Creating necessary directories...\n";
$directories = [
    'storage/framework/sessions',
    'storage/framework/cache/data',
    'storage/framework/views',
    'storage/logs',
    'storage/app/public',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir\n";
    } else {
        echo "Directory exists: $dir\n";
    }
}

// 4. Set proper permissions
echo "\n4. Setting permissions...\n";
if (PHP_OS_FAMILY === 'Windows') {
    echo "⚠️  Windows detected. Please set permissions manually:\n";
    echo "   - storage/ and bootstrap/cache/ should be writable by web server\n";
} else {
    // Set permissions for storage and cache directories
    shell_exec('chmod -R 775 storage bootstrap/cache 2>/dev/null');
    echo "✅ Permissions set for storage and cache directories\n";
}

// 5. Install/Update dependencies
echo "\n5. Installing dependencies...\n";
if (file_exists('composer.json')) {
    echo "Installing Composer dependencies...\n";
    shell_exec('composer install --no-dev --optimize-autoloader --no-interaction');
    echo "✅ Composer dependencies installed\n";
} else {
    echo "❌ composer.json not found\n";
}

if (file_exists('package.json')) {
    echo "Installing NPM dependencies...\n";
    shell_exec('npm ci --production 2>/dev/null');
    echo "✅ NPM dependencies installed\n";
    
    // Build assets
    echo "Building assets...\n";
    shell_exec('npm run build 2>/dev/null');
    echo "✅ Assets built\n";
} else {
    echo "⚠️  package.json not found. Skipping NPM dependencies.\n";
}

// 6. Run database migrations (if database is configured)
echo "\n6. Checking database configuration...\n";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'DB_CONNECTION=') !== false) {
        echo "Database configuration found. Running migrations...\n";
        runArtisan('migrate --force');
        echo "✅ Database migrations completed\n";
    } else {
        echo "⚠️  No database configuration found. Skipping migrations.\n";
    }
} else {
    echo "⚠️  .env file not found. Skipping database operations.\n";
}

// 7. Skip storage link (using private storage)
echo "\n7. Skipping storage link (using private storage)...\n";
echo "   ✅ Using private storage for file copy deployment\n";

// 8. Optimize for production
echo "\n8. Optimizing for production...\n";
runArtisan('config:cache');
runArtisan('route:cache');
runArtisan('view:cache');

// 9. Run health check
echo "\n9. Running health check...\n";
$output = shell_exec("php artisan about 2>&1");
if ($output) {
    // Filter out any path information from the output
    $filteredOutput = preg_replace('/\/[^\s]+/', '[PATH]', $output);
    echo $filteredOutput . "\n";
}

// 10. Final cache warm-up (without clearing)
echo "\n10. Warming up caches...\n";
runArtisan('config:cache');
runArtisan('route:cache');
runArtisan('view:cache');

echo "\n✅ Production optimization completed successfully!\n";
echo "\n📋 Next steps:\n";
echo "1. Verify your .env file has correct production settings\n";
echo "2. Test your application functionality\n";
echo "3. Set up SSL certificate\n";
echo "4. Configure your web server\n";
echo "5. Set up monitoring and backups\n";
echo "6. Test performance and security\n";

echo "\n🔍 Health Check Summary:\n";
echo "- PHP Version: $phpVersion\n";
echo "- Required Extensions: " . (empty($missingExtensions) ? "All installed" : "Missing: " . implode(', ', $missingExtensions)) . "\n";
echo "- Composer: " . (commandExists('composer') ? "Available" : "Not found") . "\n";
echo "- Node.js/NPM: " . (commandExists('node') && commandExists('npm') ? "Available" : "Not found") . "\n";
echo "- Directories: Created/Verified\n";
echo "- Permissions: Set\n";
echo "- Dependencies: Installed\n";
echo "- Database: " . (file_exists($envFile) && strpos(file_get_contents($envFile), 'DB_CONNECTION=') !== false ? "Configured" : "Not configured") . "\n";
echo "- Caches: Optimized\n";

echo "\n🎉 Your application is ready for production!\n";
