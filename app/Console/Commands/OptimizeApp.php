<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OptimizeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the application for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting application optimization...');
        
        // Clear all caches
        $this->info('Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        // Optimize for production
        $this->info('Optimizing for production...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        
        // Generate optimized autoloader
        $this->info('Generating optimized autoloader...');
        Artisan::call('optimize');
        
        // Clear and rebuild service worker cache
        $this->info('Updating service worker...');
        $this->updateServiceWorkerVersion();
        
        $this->info('Application optimization completed!');
    }
    
    private function updateServiceWorkerVersion()
    {
        $swPath = public_path('sw.js');
        if (File::exists($swPath)) {
            $content = File::get($swPath);
            $newVersion = 'odys-rental-v' . time();
            $content = preg_replace('/const CACHE_NAME = \'[^\']+\';/', "const CACHE_NAME = '{$newVersion}';", $content);
            File::put($swPath, $content);
        }
    }
}
