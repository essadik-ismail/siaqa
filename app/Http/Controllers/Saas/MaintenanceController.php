<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
            'last_backup' => $this->getLastBackup(),
            'cache_status' => $this->getCacheStatus(),
        ];

        $recentLogs = $this->getRecentLogs();
        $systemHealth = $this->getSystemHealth();

        return view('saas.maintenance.index', compact('systemInfo', 'recentLogs', 'systemHealth'));
    }

    public function createBackup()
    {
        try {
            $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
            
            // Create database backup
            $command = 'mysqldump -u' . config('database.connections.mysql.username') . 
                      ' -p' . config('database.connections.mysql.password') . 
                      ' -h' . config('database.connections.mysql.host') . 
                      ' ' . config('database.connections.mysql.database') . 
                      ' > ' . storage_path('backups/' . $filename);
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                Log::info('System backup created successfully: ' . $filename);
                return redirect()->back()->with('success', 'Backup created successfully: ' . $filename);
            } else {
                throw new \Exception('Backup command failed with return code: ' . $returnCode);
            }
        } catch (\Exception $e) {
            Log::error('Backup creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Backup creation failed: ' . $e->getMessage()]);
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            Log::info('System cache cleared successfully');
            return redirect()->back()->with('success', 'System cache cleared successfully');
        } catch (\Exception $e) {
            Log::error('Cache clearing failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Cache clearing failed: ' . $e->getMessage()]);
        }
    }

    public function optimize()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            Log::info('System optimized successfully');
            return redirect()->back()->with('success', 'System optimized successfully');
        } catch (\Exception $e) {
            Log::error('System optimization failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'System optimization failed: ' . $e->getMessage()]);
        }
    }

    public function logs()
    {
        $logFiles = $this->getLogFiles();
        $currentLog = request('log', 'laravel.log');
        $logContent = $this->getLogContent($currentLog);
        
        // Process log content to extract levels
        $logLines = [];
        if ($logContent !== 'Log file not found') {
            $lines = explode("\n", $logContent);
            foreach ($lines as $line) {
                if (!empty(trim($line))) {
                    $logLines[] = [
                        'content' => $line,
                        'level' => $this->getLogLevel($line)
                    ];
                }
            }
        }
        
        return view('saas.maintenance.logs', compact('logFiles', 'currentLog', 'logContent', 'logLines'));
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [config('database.connections.mysql.database')]);
            
            return $result[0]->size_mb ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getStorageUsage()
    {
        try {
            $total = disk_total_space(storage_path());
            $free = disk_free_space(storage_path());
            $used = $total - $free;
            
            return [
                'total' => round($total / 1024 / 1024 / 1024, 2),
                'used' => round($used / 1024 / 1024 / 1024, 2),
                'free' => round($free / 1024 / 1024 / 1024, 2),
                'percentage' => round(($used / $total) * 100, 2)
            ];
        } catch (\Exception $e) {
            return ['total' => 0, 'used' => 0, 'free' => 0, 'percentage' => 0];
        }
    }

    private function getLastBackup()
    {
        $backupPath = storage_path('backups');
        if (!is_dir($backupPath)) {
            return 'No backups found';
        }
        
        $files = glob($backupPath . '/*.sql');
        if (empty($files)) {
            return 'No backups found';
        }
        
        $lastFile = end($files);
        return basename($lastFile) . ' (' . date('Y-m-d H:i:s', filemtime($lastFile)) . ')';
    }

    private function getCacheStatus()
    {
        try {
            $cacheSize = 0;
            $cachePath = storage_path('framework/cache');
            
            if (is_dir($cachePath)) {
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($cachePath));
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $cacheSize += $file->getSize();
                    }
                }
            }
            
            return round($cacheSize / 1024 / 1024, 2) . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getRecentLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            if (!file_exists($logPath)) {
                return [];
            }
            
            $lines = file($logPath);
            $recentLines = array_slice($lines, -50);
            
            return array_reverse($recentLines);
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSystemHealth()
    {
        $health = [];
        
        // Check database connection
        try {
            DB::connection()->getPdo();
            $health['database'] = ['status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            $health['database'] = ['status' => 'unhealthy', 'message' => 'Connection failed'];
        }
        
        // Check storage permissions
        try {
            $testFile = storage_path('test_' . time() . '.tmp');
            file_put_contents($testFile, 'test');
            unlink($testFile);
            $health['storage'] = ['status' => 'healthy', 'message' => 'Writable'];
        } catch (\Exception $e) {
            $health['storage'] = ['status' => 'unhealthy', 'message' => 'Not writable'];
        }
        
        // Check cache
        try {
            cache()->put('health_check', 'ok', 1);
            $health['cache'] = ['status' => 'healthy', 'message' => 'Working'];
        } catch (\Exception $e) {
            $health['cache'] = ['status' => 'unhealthy', 'message' => 'Failed'];
        }
        
        return $health;
    }

    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        $files = glob($logPath . '/*.log');
        
        return array_map(function($file) {
            return basename($file);
        }, $files);
    }

    private function getLogContent($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!file_exists($logPath)) {
            return 'Log file not found';
        }
        
        $lines = file($logPath);
        $recentLines = array_slice($lines, -100);
        
        return implode('', array_reverse($recentLines));
    }

    /**
     * Get log level from a log line
     */
    public function getLogLevel($line)
    {
        if (stripos($line, 'ERROR') !== false) {
            return 'ERROR';
        } elseif (stripos($line, 'WARNING') !== false) {
            return 'WARNING';
        } elseif (stripos($line, 'INFO') !== false) {
            return 'INFO';
        } elseif (stripos($line, 'DEBUG') !== false) {
            return 'DEBUG';
        }
        
        return 'INFO';
    }
}
