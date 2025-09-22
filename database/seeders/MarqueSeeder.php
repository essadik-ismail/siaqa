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

        foreach ($tenants as $tenant) {
            $marques = [
                [
                    'marque' => 'Renault',
                    'image' => 'renault_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Peugeot',
                    'image' => 'peugeot_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Citroën',
                    'image' => 'citroen_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Volkswagen',
                    'image' => 'volkswagen_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Ford',
                    'image' => 'ford_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Opel',
                    'image' => 'opel_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'BMW',
                    'image' => 'bmw_logo.png',
                    'is_active' => true,
                ],
                [
                    'marque' => 'Mercedes-Benz',
                    'image' => 'mercedes_logo.png',
                    'is_active' => true,
                ],
            ];

            foreach ($marques as $marqueData) {
                Marque::create([
                    'tenant_id' => $tenant->id,
                    'marque' => $marqueData['marque'],
                    'image' => $marqueData['image'],
                    'is_active' => $marqueData['is_active'],
                ]);
            }
        }
    }
}