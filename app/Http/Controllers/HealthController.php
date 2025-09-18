<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    /**
     * Comprehensive health check endpoint
     */
    public function index(): JsonResponse
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'uptime' => $this->getUptime(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'checks' => []
        ];

        // Database check
        $health['checks']['database'] = $this->checkDatabase();
        
        // Cache check
        $health['checks']['cache'] = $this->checkCache();
        
        // Storage check
        $health['checks']['storage'] = $this->checkStorage();
        
        // Session check
        $health['checks']['session'] = $this->checkSession();
        
        // Memory check
        $health['checks']['memory'] = $this->checkMemory();
        
        // Disk space check
        $health['checks']['disk'] = $this->checkDiskSpace();
        
        // Private image storage check
        $health['checks']['private_images'] = $this->checkPrivateImages();
        
        // Overall status
        $allHealthy = collect($health['checks'])->every(fn($check) => $check['status'] === 'healthy');
        $health['status'] = $allHealthy ? 'healthy' : 'unhealthy';

        $statusCode = $allHealthy ? 200 : 503;
        
        return response()->json($health, $statusCode);
    }

    /**
     * Simple health check for load balancers
     */
    public function simple(): JsonResponse
    {
        try {
            // Quick database ping
            DB::connection()->getPdo();
            
            return response()->json([
                'status' => 'ok',
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'timestamp' => now()->toISOString(),
                'error' => 'Database connection failed'
            ], 503);
        }
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            return [
                'status' => 'healthy',
                'response_time_ms' => $responseTime,
                'connection' => 'ok'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connection' => 'failed'
            ];
        }
    }

    /**
     * Check cache system
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test_value';
            
            // Test cache write
            Cache::put($key, $value, 60);
            
            // Test cache read
            $retrieved = Cache::get($key);
            
            // Clean up
            Cache::forget($key);
            
            if ($retrieved === $value) {
                return [
                    'status' => 'healthy',
                    'driver' => config('cache.default'),
                    'connection' => 'ok'
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Cache read/write test failed',
                    'driver' => config('cache.default')
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('cache.default')
            ];
        }
    }

    /**
     * Check storage system
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            $testContent = 'health check test';
            
            // Test write
            Storage::disk('local')->put($testFile, $testContent);
            
            // Test read
            $retrieved = Storage::disk('local')->get($testFile);
            
            // Clean up
            Storage::disk('local')->delete($testFile);
            
            if ($retrieved === $testContent) {
                return [
                    'status' => 'healthy',
                    'driver' => 'local',
                    'connection' => 'ok'
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Storage read/write test failed',
                    'driver' => 'local'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => 'local'
            ];
        }
    }

    /**
     * Check session system
     */
    private function checkSession(): array
    {
        try {
            $driver = config('session.driver');
            
            if ($driver === 'file') {
                $sessionPath = storage_path('framework/sessions');
                if (!is_dir($sessionPath) || !is_writable($sessionPath)) {
                    return [
                        'status' => 'unhealthy',
                        'error' => 'Session directory not writable',
                        'driver' => $driver,
                        'path' => $sessionPath
                    ];
                }
            }
            
            return [
                'status' => 'healthy',
                'driver' => $driver,
                'connection' => 'ok'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'driver' => config('session.driver')
            ];
        }
    }

    /**
     * Check memory usage
     */
    private function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);
        $memoryPercent = round(($memoryUsage / $memoryLimitBytes) * 100, 2);
        
        $status = $memoryPercent > 90 ? 'unhealthy' : ($memoryPercent > 75 ? 'warning' : 'healthy');
        
        return [
            'status' => $status,
            'usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'limit_mb' => round($memoryLimitBytes / 1024 / 1024, 2),
            'percent' => $memoryPercent
        ];
    }

    /**
     * Check disk space
     */
    private function checkDiskSpace(): array
    {
        $path = storage_path();
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;
        $usedPercent = round(($usedBytes / $totalBytes) * 100, 2);
        
        $status = $usedPercent > 90 ? 'unhealthy' : ($usedPercent > 80 ? 'warning' : 'healthy');
        
        return [
            'status' => $status,
            'total_gb' => round($totalBytes / 1024 / 1024 / 1024, 2),
            'free_gb' => round($freeBytes / 1024 / 1024 / 1024, 2),
            'used_percent' => $usedPercent
        ];
    }

    /**
     * Check private image storage
     */
    private function checkPrivateImages(): array
    {
        try {
            $imagePath = storage_path('app/images');
            
            if (!is_dir($imagePath)) {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Private images directory does not exist',
                    'path' => $imagePath
                ];
            }
            
            if (!is_writable($imagePath)) {
                return [
                    'status' => 'unhealthy',
                    'error' => 'Private images directory is not writable',
                    'path' => $imagePath
                ];
            }
            
            return [
                'status' => 'healthy',
                'path' => $imagePath,
                'writable' => true
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get system uptime
     */
    private function getUptime(): string
    {
        if (function_exists('sys_getloadavg')) {
            $uptime = shell_exec('uptime -p 2>/dev/null') ?: 'Unknown';
            return trim($uptime);
        }
        
        return 'Unknown';
    }

    /**
     * Convert memory limit string to bytes
     */
    private function convertToBytes(string $memoryLimit): int
    {
        $memoryLimit = trim($memoryLimit);
        $last = strtolower($memoryLimit[strlen($memoryLimit) - 1]);
        $memoryLimit = (int) $memoryLimit;
        
        switch ($last) {
            case 'g':
                $memoryLimit *= 1024;
            case 'm':
                $memoryLimit *= 1024;
            case 'k':
                $memoryLimit *= 1024;
        }
        
        return $memoryLimit;
    }
}
