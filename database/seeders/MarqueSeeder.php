<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;
use App\Models\Tenant;

class MarqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Please run TenantSeeder first.');
            return;
        }

        $marques = [
            'Renault',
            'Peugeot',
            'Citroën',
            'Volkswagen',
            'Ford',
            'Opel',
            'Fiat',
            'Toyota',
            'Nissan',
            'Honda',
            'Hyundai',
            'Kia',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Skoda',
            'Seat',
            'Dacia',
        ];

        $this->command->info('Seeding marques for ' . $tenants->count() . ' tenant(s)...');

        foreach ($tenants as $tenant) {
            $this->command->info("Creating marques for tenant: {$tenant->name}");
            
            foreach ($marques as $marqueName) {
                // Check if marque already exists for this tenant
                $existingMarque = Marque::where('tenant_id', $tenant->id)
                    ->where('marque', $marqueName)
                    ->first();

                if (!$existingMarque) {
                    Marque::create([
                        'tenant_id' => $tenant->id,
                        'marque' => $marqueName,
                        'is_active' => true,
                    ]);
                    $this->command->line("  ✓ Created: {$marqueName}");
                } else {
                    $this->command->line("  - Skipped: {$marqueName} (already exists)");
                }
            }
        }

        $this->command->info('Marque seeding completed!');
    }
}
