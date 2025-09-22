<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Tenant;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $packages = [
                [
                    'name' => 'Pack Débutant',
                    'description' => 'Pack idéal pour les débutants avec accompagnement renforcé',
                    'license_category' => 'B',
                    'theory_hours' => 20,
                    'practical_hours' => 20,
                    'price' => 1200.00,
                    'is_active' => true,
                    'includes_exam' => true,
                    'includes_materials' => true,
                    'features' => [
                        'Cours de code illimités',
                        '20h de conduite',
                        'Accompagnement personnalisé',
                        'Support pédagogique',
                        'Simulateur d\'examen'
                    ],
                    'validity_days' => 365,
                ],
                [
                    'name' => 'Pack Standard',
                    'description' => 'Pack standard avec un bon équilibre théorie/pratique',
                    'license_category' => 'B',
                    'theory_hours' => 15,
                    'practical_hours' => 15,
                    'price' => 900.00,
                    'is_active' => true,
                    'includes_exam' => false,
                    'includes_materials' => true,
                    'features' => [
                        'Cours de code',
                        '15h de conduite',
                        'Suivi personnalisé',
                        'Matériel pédagogique'
                    ],
                    'validity_days' => 365,
                ],
                [
                    'name' => 'Pack Intensif',
                    'description' => 'Pack accéléré pour obtenir le permis rapidement',
                    'license_category' => 'B',
                    'theory_hours' => 25,
                    'practical_hours' => 30,
                    'price' => 1800.00,
                    'is_active' => true,
                    'includes_exam' => true,
                    'includes_materials' => true,
                    'features' => [
                        'Cours de code intensifs',
                        '30h de conduite',
                        'Coaching personnalisé',
                        'Préparation aux examens',
                        'Suivi quotidien',
                        'Garantie de réussite'
                    ],
                    'validity_days' => 180,
                ],
                [
                    'name' => 'Pack Perfectionnement',
                    'description' => 'Pack pour améliorer ses compétences de conduite',
                    'license_category' => 'B',
                    'theory_hours' => 10,
                    'practical_hours' => 10,
                    'price' => 600.00,
                    'is_active' => true,
                    'includes_exam' => false,
                    'includes_materials' => false,
                    'features' => [
                        '10h de conduite',
                        'Techniques avancées',
                        'Conduite défensive',
                        'Préparation aux situations difficiles'
                    ],
                    'validity_days' => 90,
                ],
                [
                    'name' => 'Pack Moto A1',
                    'description' => 'Pack spécialisé pour le permis moto A1',
                    'license_category' => 'A1',
                    'theory_hours' => 15,
                    'practical_hours' => 20,
                    'price' => 1000.00,
                    'is_active' => true,
                    'includes_exam' => true,
                    'includes_materials' => true,
                    'features' => [
                        'Cours théoriques moto',
                        '20h de conduite moto',
                        'Équipement de sécurité',
                        'Techniques spécifiques moto'
                    ],
                    'validity_days' => 365,
                ],
            ];

            foreach ($packages as $packageData) {
                Package::create([
                    'tenant_id' => $tenant->id,
                    'name' => $packageData['name'],
                    'description' => $packageData['description'],
                    'license_category' => $packageData['license_category'],
                    'theory_hours' => $packageData['theory_hours'],
                    'practical_hours' => $packageData['practical_hours'],
                    'price' => $packageData['price'],
                    'is_active' => $packageData['is_active'],
                    'includes_exam' => $packageData['includes_exam'],
                    'includes_materials' => $packageData['includes_materials'],
                    'features' => $packageData['features'],
                    'validity_days' => $packageData['validity_days'],
                ]);
            }
        }
    }
}