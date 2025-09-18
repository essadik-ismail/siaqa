<?php
/**
 * Fix Sessions Directory for Windows
 * Creates sessions directory with proper permissions
 */

echo "🔧 Fixing sessions directory for Windows...\n";

$sessionsDir = 'storage/framework/sessions';

// Create directory if it doesn't exist
if (!is_dir($sessionsDir)) {
    if (mkdir($sessionsDir, 0755, true)) {
        echo "✅ Created: $sessionsDir\n";
    } else {
        echo "❌ Failed to create: $sessionsDir\n";
        exit(1);
    }
} else {
    echo "✅ Directory exists: $sessionsDir\n";
}

// Set permissions (Windows compatible)
if (is_dir($sessionsDir)) {
    // Make directory writable
    chmod($sessionsDir, 0755);
    echo "✅ Set permissions: $sessionsDir\n";
    
    // Test if directory is writable'
    $testFile = $sessionsDir . '/test_write.tmp';
    if (file_put_contents($testFile, 'test') !== false) {
        unlink($testFile);
        echo "✅ Directory is writable: $sessionsDir\n";
    } else {
        echo "❌ Directory is not writable: $sessionsDir\n";
        echo "💡 Try running as administrator or check folder permissions\n";
    }
}

// Create .gitignore file to prevent session files from being committed
$gitignoreFile = $sessionsDir . '/.gitignore';
if (!file_exists($gitignoreFile)) {
    file_put_contents($gitignoreFile, "*\n!.gitignore\n");
    echo "✅ Created .gitignore in sessions directory\n";
}

// Check if we can create a test session file
$testSessionFile = $sessionsDir . '/test_session_' . time() . '.txt';
if (file_put_contents($testSessionFile, 'test session data') !== false) {
    unlink($testSessionFile);
    echo "✅ Session file creation test passed\n";
} else {
    echo "❌ Session file creation test failed\n";
    echo "💡 Check directory permissions and try again\n";
}

echo "\n🎉 Sessions directory setup complete!\n";
echo "📁 Sessions directory: $sessionsDir\n";
echo "🔧 Session driver: file\n";
echo "✅ Ready for production!\n";
