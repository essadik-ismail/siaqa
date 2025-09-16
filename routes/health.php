<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
|
| These routes are used for monitoring and health checks in production.
| They should be accessible without authentication and provide basic
| system status information.
|
*/

Route::get('/health', function () {
    $status = [
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
    ];

    // Check database connection
    try {
        DB::connection()->getPdo();
        $status['database'] = 'connected';
    } catch (Exception $e) {
        $status['database'] = 'error';
        $status['status'] = 'error';
        $status['errors'][] = 'Database connection failed: ' . $e->getMessage();
    }

    // Check cache
    try {
        Cache::put('health_check', 'ok', 60);
        $cacheStatus = Cache::get('health_check');
        $status['cache'] = $cacheStatus === 'ok' ? 'working' : 'error';
    } catch (Exception $e) {
        $status['cache'] = 'error';
        $status['status'] = 'error';
        $status['errors'][] = 'Cache system failed: ' . $e->getMessage();
    }

    // Check storage
    try {
        Storage::disk('local')->put('health_check.txt', 'ok');
        $storageStatus = Storage::disk('local')->get('health_check.txt');
        Storage::disk('local')->delete('health_check.txt');
        $status['storage'] = $storageStatus === 'ok' ? 'working' : 'error';
    } catch (Exception $e) {
        $status['storage'] = 'error';
        $status['status'] = 'error';
        $status['errors'][] = 'Storage system failed: ' . $e->getMessage();
    }

    // Check disk space
    $diskFreeSpace = disk_free_space(storage_path());
    $diskTotalSpace = disk_total_space(storage_path());
    $diskUsagePercent = round((($diskTotalSpace - $diskFreeSpace) / $diskTotalSpace) * 100, 2);
    
    $status['disk'] = [
        'free_space' => round($diskFreeSpace / 1024 / 1024, 2) . ' MB',
        'total_space' => round($diskTotalSpace / 1024 / 1024, 2) . ' MB',
        'usage_percent' => $diskUsagePercent . '%',
        'status' => $diskUsagePercent > 90 ? 'warning' : 'ok',
    ];

    // Check memory usage
    $memoryUsage = memory_get_usage(true);
    $memoryPeak = memory_get_peak_usage(true);
    
    $status['memory'] = [
        'current' => round($memoryUsage / 1024 / 1024, 2) . ' MB',
        'peak' => round($memoryPeak / 1024 / 1024, 2) . ' MB',
        'status' => $memoryPeak > (128 * 1024 * 1024) ? 'warning' : 'ok', // Warning if > 128MB
    ];

    // Check PHP version
    $status['php'] = [
        'version' => PHP_VERSION,
        'status' => version_compare(PHP_VERSION, '8.2.0', '>=') ? 'ok' : 'warning',
    ];

    // Check Laravel version
    $status['laravel'] = [
        'version' => app()->version(),
        'status' => 'ok',
    ];

    // Overall status
    if ($status['status'] === 'ok' && 
        $status['database'] === 'connected' && 
        $status['cache'] === 'working' && 
        $status['storage'] === 'working') {
        $status['overall'] = 'healthy';
    } else {
        $status['overall'] = 'unhealthy';
    }

    $httpStatus = $status['overall'] === 'healthy' ? 200 : 503;
    
    return response()->json($status, $httpStatus);
})->name('health.check');

Route::get('/health/simple', function () {
    try {
        // Simple database check
        DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'timestamp' => now()->toISOString(),
            'error' => 'Database connection failed',
        ], 503);
    }
})->name('health.simple');
