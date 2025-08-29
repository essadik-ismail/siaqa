<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all permissions
        $permissions = Permission::all()->keyBy('name');
        
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $consultantRole = Role::where('name', 'consultant')->first();

        // Assign permissions to Super Admin (all permissions)
        if ($superAdminRole) {
            $superAdminRole->assignPermissions($permissions->pluck('id')->toArray());
        }

        // Assign permissions to Admin (all except SaaS and user/role management)
        if ($adminRole) {
            $adminPermissions = $permissions->filter(function ($permission) {
                return !str_starts_with($permission->name, 'saas.') &&
                       !str_starts_with($permission->name, 'users.') &&
                       !str_starts_with($permission->name, 'roles.');
            })->pluck('id')->toArray();
            
            $adminRole->assignPermissions($adminPermissions);
        }

        // Assign permissions to Consultant (view permissions only)
        if ($consultantRole) {
            $consultantPermissions = $permissions->filter(function ($permission) {
                return str_ends_with($permission->name, '.view') ||
                       str_ends_with($permission->name, '.analytics') ||
                       in_array($permission->name, [
                           'dashboard.view',
                           'dashboard.analytics',
                           'clients.view',
                           'agencies.view',
                           'vehicles.view',
                           'brands.view',
                           'reservations.view',
                           'contracts.view',
                           'insurances.view',
                           'maintenance.view',
                           'charges.view',
                           'notifications.view',
                           'settings.view',
                       ]);
            })->pluck('id')->toArray();
            
            $consultantRole->assignPermissions($consultantPermissions);
        }

        // Create default users
        $this->createDefaultUsers();
    }

    /**
     * Create default users with roles
     */
    private function createDefaultUsers(): void
    {
        // Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@rental.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdmin->assignRoles([$superAdminRole->id]);
        }

        // Create Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@rental.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->assignRoles([$adminRole->id]);
        }

        // Create Consultant user
        $consultant = User::updateOrCreate(
            ['email' => 'consultant@rental.com'],
            [
                'name' => 'Consultant',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $consultantRole = Role::where('name', 'consultant')->first();
        if ($consultantRole) {
            $consultant->assignRoles([$consultantRole->id]);
        }
    }
}
