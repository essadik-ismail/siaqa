<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with SaaS management capabilities',
                'tenant_id' => null, // System role
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full access to all car rental operations within the tenant',
                'tenant_id' => null, // Will be set per tenant
            ],
            [
                'name' => 'consultant',
                'display_name' => 'Consultant',
                'description' => 'Limited access for viewing and basic operations',
                'tenant_id' => null, // Will be set per tenant
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
