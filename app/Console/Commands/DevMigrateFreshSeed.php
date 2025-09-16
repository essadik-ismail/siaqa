<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DevMigrateFreshSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:migrate-fresh-seed {--timeout=600 : Maximum execution time in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run fresh migrations and seeders for development with extended timeout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if we're in development environment
        if (!app()->environment('local', 'development')) {
            $this->error('This command is only available in development environment');
            return 1;
        }

        $timeout = $this->option('timeout');
        
        // Set execution time limit
        set_time_limit($timeout);
        ini_set('memory_limit', '1024M');

        $this->info("Starting fresh migration and seeding with {$timeout}s timeout...");

        try {
            // Disable foreign key checks for faster migration
            $this->info('Disabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Run fresh migrations
            $this->info('Running fresh migrations...');
            $migrationExitCode = Artisan::call('migrate:fresh', ['--force' => true]);
            
            if ($migrationExitCode !== 0) {
                $this->error('Migration failed with exit code: ' . $migrationExitCode);
                $this->error(Artisan::output());
                return $migrationExitCode;
            }

            $this->info('Migrations completed successfully');

            // Re-enable foreign key checks
            $this->info('Re-enabling foreign key checks...');
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Run seeders
            $this->info('Running seeders...');
            $seederExitCode = Artisan::call('db:seed', ['--force' => true]);
            
            if ($seederExitCode !== 0) {
                $this->error('Seeding failed with exit code: ' . $seederExitCode);
                $this->error(Artisan::output());
                return $seederExitCode;
            }

            $this->info('Seeders completed successfully');
            $this->info('✅ Fresh migration and seeding completed successfully!');

            return 0;

        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->error('❌ Error during migration/seeding: ' . $e->getMessage());
            return 1;
        }
    }
}
