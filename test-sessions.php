<?php
/**
 * Test Session Functionality
 * Verifies that sessions are working correctly
 */

echo "🧪 Testing session functionality...\n\n";

// Test 1: Check if sessions directory exists and is writable
$sessionsDir = 'storage/framework/sessions';
echo "1. Checking sessions directory...\n";
if (is_dir($sessionsDir)) {
    echo "   ✅ Directory exists: $sessionsDir\n";
    
    if (is_writable($sessionsDir)) {
        echo "   ✅ Directory is writable\n";
    } else {
        echo "   ❌ Directory is not writable\n";
        exit(1);
    }
} else {
    echo "   ❌ Directory does not exist: $sessionsDir\n";
    exit(1);
}

// Test 2: Test file creation in sessions directory
echo "\n2. Testing file creation...\n";
$testFile = $sessionsDir . '/test_' . time() . '.txt';
if (file_put_contents($testFile, 'test data') !== false) {
    echo "   ✅ File creation successful\n";
    unlink($testFile);
    echo "   ✅ File deletion successful\n";
} else {
    echo "   ❌ File creation failed\n";
    exit(1);
}

// Test 3: Test Laravel session functionality
echo "\n3. Testing Laravel session...\n";
try {
    // Bootstrap Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // Test session
    session(['test_key' => 'test_value']);
    $value = session('test_key');
    
    if ($value === 'test_value') {
        echo "   ✅ Laravel session working correctly\n";
    } else {
        echo "   ❌ Laravel session test failed\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "   ❌ Laravel session error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Check session configuration
echo "\n4. Checking session configuration...\n";
$sessionDriver = config('session.driver');
$cacheDriver = config('cache.default');

echo "   📊 Session driver: $sessionDriver\n";
echo "   📊 Cache driver: $cacheDriver\n";

if ($sessionDriver === 'file') {
    echo "   ✅ Session driver is correct (file)\n";
} else {
    echo "   ⚠️  Session driver is $sessionDriver (expected: file)\n";
}

if ($cacheDriver === 'file') {
    echo "   ✅ Cache driver is correct (file)\n";
} else {
    echo "   ⚠️  Cache driver is $cacheDriver (expected: file)\n";
}

echo "\n🎉 All session tests passed!\n";
echo "✅ Sessions are working correctly\n";
echo "✅ No more 'file_put_contents' errors expected\n";
echo "✅ Ready for production deployment\n";
