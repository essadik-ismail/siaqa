<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'description' => 'Can view the main dashboard', 'module' => 'dashboard'],
            ['name' => 'dashboard.analytics', 'display_name' => 'View Analytics', 'description' => 'Can view detailed analytics and reports', 'module' => 'dashboard'],
            
            // User management permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'Can view user list', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Can create new users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Can edit existing users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Can delete users', 'module' => 'users'],
            ['name' => 'users.roles', 'display_name' => 'Manage User Roles', 'description' => 'Can assign roles to users', 'module' => 'users'],
            
            // Role management permissions
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'Can view role list', 'module' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Can create new roles', 'module' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Can edit existing roles', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles', 'module' => 'roles'],
            ['name' => 'roles.permissions', 'display_name' => 'Manage Role Permissions', 'description' => 'Can assign permissions to roles', 'module' => 'roles'],
            
            // Client management permissions
            ['name' => 'clients.view', 'display_name' => 'View Clients', 'description' => 'Can view client list', 'module' => 'clients'],
            ['name' => 'clients.create', 'display_name' => 'Create Clients', 'description' => 'Can create new clients', 'module' => 'clients'],
            ['name' => 'clients.edit', 'display_name' => 'Edit Clients', 'description' => 'Can edit existing clients', 'module' => 'clients'],
            ['name' => 'clients.delete', 'display_name' => 'Delete Clients', 'description' => 'Can delete clients', 'module' => 'clients'],
            ['name' => 'clients.blacklist', 'display_name' => 'Manage Blacklist', 'description' => 'Can manage client blacklist', 'module' => 'clients'],
            
            // Agency management permissions
            ['name' => 'agencies.view', 'display_name' => 'View Agencies', 'description' => 'Can view agency list', 'module' => 'agencies'],
            ['name' => 'agencies.create', 'display_name' => 'Create Agencies', 'description' => 'Can create new agencies', 'module' => 'agencies'],
            ['name' => 'agencies.edit', 'display_name' => 'Edit Agencies', 'description' => 'Can edit existing agencies', 'module' => 'agencies'],
            ['name' => 'agencies.delete', 'display_name' => 'Delete Agencies', 'description' => 'Can delete agencies', 'module' => 'agencies'],
            
            // Vehicle management permissions
            ['name' => 'vehicles.view', 'display_name' => 'View Vehicles', 'description' => 'Can view vehicle list', 'module' => 'vehicles'],
            ['name' => 'vehicles.create', 'display_name' => 'Create Vehicles', 'description' => 'Can create new vehicles', 'module' => 'vehicles'],
            ['name' => 'vehicles.edit', 'display_name' => 'Edit Vehicles', 'description' => 'Can edit existing vehicles', 'module' => 'vehicles'],
            ['name' => 'vehicles.delete', 'display_name' => 'Delete Vehicles', 'description' => 'Can delete vehicles', 'module' => 'vehicles'],
            ['name' => 'vehicles.status', 'display_name' => 'Manage Vehicle Status', 'description' => 'Can change vehicle status', 'module' => 'vehicles'],
            
            // Brand management permissions
            ['name' => 'brands.view', 'display_name' => 'View Brands', 'description' => 'Can view brand list', 'module' => 'brands'],
            ['name' => 'brands.create', 'display_name' => 'Create Brands', 'description' => 'Can create new brands', 'module' => 'brands'],
            ['name' => 'brands.edit', 'display_name' => 'Edit Brands', 'description' => 'Can edit existing brands', 'module' => 'brands'],
            ['name' => 'brands.delete', 'display_name' => 'Delete Brands', 'description' => 'Can delete brands', 'module' => 'brands'],
            
            // Reservation management permissions
            ['name' => 'reservations.view', 'display_name' => 'View Reservations', 'description' => 'Can view reservation list', 'module' => 'reservations'],
            ['name' => 'reservations.create', 'display_name' => 'Create Reservations', 'description' => 'Can create new reservations', 'module' => 'reservations'],
            ['name' => 'reservations.edit', 'display_name' => 'Edit Reservations', 'description' => 'Can edit existing reservations', 'module' => 'reservations'],
            ['name' => 'reservations.delete', 'display_name' => 'Delete Reservations', 'description' => 'Can delete reservations', 'module' => 'reservations'],
            ['name' => 'reservations.confirm', 'display_name' => 'Confirm Reservations', 'description' => 'Can confirm reservations', 'module' => 'reservations'],
            ['name' => 'reservations.cancel', 'display_name' => 'Cancel Reservations', 'description' => 'Can cancel reservations', 'module' => 'reservations'],
            
            // Contract management permissions
            ['name' => 'contracts.view', 'display_name' => 'View Contracts', 'description' => 'Can view contract list', 'module' => 'contracts'],
            ['name' => 'contracts.create', 'display_name' => 'Create Contracts', 'description' => 'Can create new contracts', 'module' => 'contracts'],
            ['name' => 'contracts.edit', 'display_name' => 'Edit Contracts', 'description' => 'Can edit existing contracts', 'module' => 'contracts'],
            ['name' => 'contracts.delete', 'display_name' => 'Delete Contracts', 'description' => 'Can delete contracts', 'module' => 'contracts'],
            ['name' => 'contracts.sign', 'display_name' => 'Sign Contracts', 'description' => 'Can sign contracts', 'module' => 'contracts'],
            ['name' => 'contracts.terminate', 'display_name' => 'Terminate Contracts', 'description' => 'Can terminate contracts', 'module' => 'contracts'],
            
            // Insurance management permissions
            ['name' => 'insurances.view', 'display_name' => 'View Insurances', 'description' => 'Can view insurance list', 'module' => 'insurances'],
            ['name' => 'insurances.create', 'display_name' => 'Create Insurances', 'description' => 'Can create new insurances', 'module' => 'insurances'],
            ['name' => 'insurances.edit', 'display_name' => 'Edit Insurances', 'description' => 'Can edit existing insurances', 'module' => 'insurances'],
            ['name' => 'insurances.delete', 'display_name' => 'Delete Insurances', 'description' => 'Can delete insurances', 'module' => 'insurances'],
            ['name' => 'insurances.renew', 'display_name' => 'Renew Insurances', 'description' => 'Can renew insurances', 'module' => 'insurances'],
            
            // Maintenance permissions
            ['name' => 'maintenance.view', 'display_name' => 'View Maintenance', 'description' => 'Can view maintenance records', 'module' => 'maintenance'],
            ['name' => 'maintenance.create', 'display_name' => 'Create Maintenance', 'description' => 'Can create maintenance records', 'module' => 'maintenance'],
            ['name' => 'maintenance.edit', 'display_name' => 'Edit Maintenance', 'description' => 'Can edit maintenance records', 'module' => 'maintenance'],
            ['name' => 'maintenance.delete', 'display_name' => 'Delete Maintenance', 'description' => 'Can delete maintenance records', 'module' => 'maintenance'],
            ['name' => 'maintenance.complete', 'display_name' => 'Complete Maintenance', 'description' => 'Can mark maintenance as complete', 'module' => 'maintenance'],
            
            // Financial permissions
            ['name' => 'charges.view', 'display_name' => 'View Charges', 'description' => 'Can view charges', 'module' => 'financial'],
            ['name' => 'charges.create', 'display_name' => 'Create Charges', 'description' => 'Can create charges', 'module' => 'financial'],
            ['name' => 'charges.edit', 'display_name' => 'Edit Charges', 'description' => 'Can edit charges', 'module' => 'financial'],
            ['name' => 'charges.delete', 'display_name' => 'Delete Charges', 'description' => 'Can delete charges', 'module' => 'financial'],
            ['name' => 'financial.reports', 'display_name' => 'View Financial Reports', 'description' => 'Can view financial reports', 'module' => 'financial'],
            
            // Notification permissions
            ['name' => 'notifications.view', 'display_name' => 'View Notifications', 'description' => 'Can view notifications', 'module' => 'notifications'],
            ['name' => 'notifications.create', 'display_name' => 'Create Notifications', 'description' => 'Can create notifications', 'module' => 'notifications'],
            ['name' => 'notifications.edit', 'display_name' => 'Edit Notifications', 'description' => 'Can edit notifications', 'module' => 'notifications'],
            ['name' => 'notifications.delete', 'display_name' => 'Delete Notifications', 'description' => 'Can delete notifications', 'module' => 'notifications'],
            
            // Settings permissions
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'Can view system settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'description' => 'Can edit system settings', 'module' => 'settings'],
            
            // SaaS permissions (for super admin)
            ['name' => 'saas.tenants', 'display_name' => 'Manage Tenants', 'description' => 'Can manage SaaS tenants', 'module' => 'saas'],
            ['name' => 'saas.subscriptions', 'display_name' => 'Manage Subscriptions', 'description' => 'Can manage SaaS subscriptions', 'module' => 'saas'],
            ['name' => 'saas.billing', 'display_name' => 'Manage Billing', 'description' => 'Can manage SaaS billing', 'module' => 'saas'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
