<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TenantCreationService
{
    public function createTenant(array $data)
    {
        DB::beginTransaction();

        try {
            // Create tenant record
            $tenant = Tenant::create([
                'name' => $data['name'],
                'domain' => $data['domain'],
                'database' => $this->generateDatabaseName($data['domain']),
                'subscription_plan' => $data['subscription_plan'] ?? 'starter',
                'trial_ends_at' => now()->addDays(14),
                'is_active' => true,
            ]);

            // Create tenant database
            $this->createTenantDatabase($tenant);

            // Run migrations for tenant
            $this->runTenantMigrations($tenant);

            // Seed tenant data
            $this->seedTenantData($tenant);

            DB::commit();

            Log::info("Tenant created successfully: {$tenant->domain}");

            return $tenant;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create tenant: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTenant(Tenant $tenant)
    {
        DB::beginTransaction();

        try {
            // Drop tenant database
            $this->dropTenantDatabase($tenant);

            // Delete tenant record
            $tenant->delete();

            DB::commit();

            Log::info("Tenant deleted successfully: {$tenant->domain}");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete tenant: " . $e->getMessage());
            throw $e;
        }
    }

    public function suspendTenant(Tenant $tenant)
    {
        $tenant->update(['is_active' => false]);
        Log::info("Tenant suspended: {$tenant->domain}");
    }

    public function activateTenant(Tenant $tenant)
    {
        $tenant->update(['is_active' => true]);
        Log::info("Tenant activated: {$tenant->domain}");
    }

    protected function generateDatabaseName(string $domain): string
    {
        $cleanDomain = strtolower(str_replace([' ', '-', '.'], '_', $domain));
        return 'tenant_' . $cleanDomain . '_' . time();
    }

    protected function createTenantDatabase(Tenant $tenant)
    {
        DB::statement("CREATE DATABASE IF NOT EXISTS {$tenant->database}");
    }

    protected function dropTenantDatabase(Tenant $tenant)
    {
        DB::statement("DROP DATABASE IF EXISTS {$tenant->database}");
    }

    protected function runTenantMigrations(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    protected function seedTenantData(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        // Create default roles
        $this->createDefaultRoles($tenant);
        
        // Create default admin user
        $this->createDefaultAdmin($tenant);
    }

    protected function createDefaultRoles(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        DB::connection('tenant')->table('roles')->insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manager', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    protected function createDefaultAdmin(Tenant $tenant)
    {
        config(['database.connections.tenant.database' => $tenant->database]);
        
        $adminRole = DB::connection('tenant')->table('roles')->where('name', 'admin')->first();
        
        DB::connection('tenant')->table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@' . $tenant->domain,
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 