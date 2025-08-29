<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Marque;
use App\Models\Vehicule;
use App\Models\Agence;
use App\Models\User;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default tenant if none exists
        $tenant = Tenant::firstOrCreate(
            ['domain' => 'localhost'],
            [
                'name' => 'Default Tenant',
                'domain' => 'localhost',
                'database' => 'app_rental',
                'subscription_plan' => 'enterprise',
                'is_active' => true,
            ]
        );

        // Update existing marques with tenant_id
        Marque::whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);

        // Update existing vehicles with tenant_id
        Vehicule::whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);

        // Update existing agencies with tenant_id
        Agence::whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);

        // Update existing users with tenant_id
        User::whereNull('tenant_id')->update(['tenant_id' => $tenant->id]);

        // Set some vehicles to display on landing page
        Vehicule::where('tenant_id', $tenant->id)
            ->where('statut', 'disponible')
            ->where('is_active', true)
            ->limit(6)
            ->update(['landing_display' => true]);

        // Set landing order for vehicles
        $vehicles = Vehicule::where('tenant_id', $tenant->id)
            ->where('landing_display', true)
            ->orderBy('name')
            ->get();

        foreach ($vehicles as $index => $vehicle) {
            $vehicle->update(['landing_order' => $index + 1]);
        }

        $this->command->info("Default tenant created and existing data updated successfully!");
    }
}
