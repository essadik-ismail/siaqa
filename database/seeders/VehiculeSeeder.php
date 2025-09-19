<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicule;
use App\Models\Marque;
use App\Models\Tenant;

class VehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $marques = Marque::where('tenant_id', $tenant->id)->get();
            
            if ($marques->isEmpty()) {
                continue;
            }

            $vehicles = [
                [
                    'name' => 'Clio V',
                    'immatriculation' => 'AB-123-CD',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Blanc',
                    'description' => 'Véhicule idéal pour l\'apprentissage de la conduite',
                ],
                [
                    'name' => '208',
                    'immatriculation' => 'EF-456-GH',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Rouge',
                    'description' => 'Compact et maniable, parfait pour débuter',
                ],
                [
                    'name' => 'C3',
                    'immatriculation' => 'IJ-789-KL',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Bleu',
                    'description' => 'Confortable et économique',
                ],
                [
                    'name' => 'Golf',
                    'immatriculation' => 'MN-012-OP',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Gris',
                    'description' => 'Qualité allemande, très fiable',
                ],
                [
                    'name' => 'Fiesta',
                    'immatriculation' => 'QR-345-ST',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Noir',
                    'description' => 'En maintenance - révision technique',
                    'requires_maintenance' => true,
                ],
                [
                    'name' => 'Corsa',
                    'immatriculation' => 'UV-678-WX',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Vert',
                    'description' => 'Économique et facile à conduire',
                ],
                [
                    'name' => 'Punto',
                    'immatriculation' => 'YZ-901-AB',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Jaune',
                    'description' => 'Petit gabarit, idéal pour la ville',
                ],
                [
                    'name' => 'Yaris',
                    'immatriculation' => 'CD-234-EF',
                    'categorie_vehicule' => 'B',
                    'couleur' => 'Argent',
                    'description' => 'Hybride, très économique',
                ],
            ];

            foreach ($vehicles as $index => $vehicleData) {
                $marque = $marques->random();
                
                Vehicule::create([
                    'tenant_id' => $tenant->id,
                    'marque_id' => $marque->id,
                    'name' => $vehicleData['name'],
                    'immatriculation' => $vehicleData['immatriculation'],
                    'is_active' => true,
                    'landing_display' => $index < 4, // First 4 vehicles displayed on landing
                    'landing_order' => $index + 1,
                    'categorie_vehicule' => $vehicleData['categorie_vehicule'],
                    'couleur' => $vehicleData['couleur'],
                    'description' => $vehicleData['description'],
                    'requires_maintenance' => $vehicleData['requires_maintenance'] ?? false,
                ]);
            }
        }
    }
}
