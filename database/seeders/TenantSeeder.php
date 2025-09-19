<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            [
                'name' => 'Auto École Excellence',
                'companyName' => 'Auto École Excellence SARL',
                'address' => '123 Avenue des Champs-Élysées, 75008 Paris',
                'tel' => '+33 1 42 36 78 90',
                'gsm' => '+33 6 12 34 56 78',
                'email' => 'contact@autoecole-excellence.fr',
                'website' => '127.0.0.1:8000',
                'subscription_plan' => 'premium',
                'is_active' => true,
                'settings' => json_encode([
                    'currency' => 'EUR',
                    'timezone' => 'Europe/Paris',
                    'language' => 'fr',
                    'driving_license_types' => ['B', 'A1', 'A2', 'A'],
                    'lesson_duration' => 60,
                    'max_students_per_instructor' => 8,
                    'auto_booking_enabled' => true,
                    'notification_email' => 'notifications@autoecole-excellence.fr',
                ]),
            ],
            [
                'name' => 'Conduite Moderne',
                'companyName' => 'Conduite Moderne SAS',
                'address' => '456 Rue de la République, 69002 Lyon',
                'tel' => '+33 4 78 12 34 56',
                'gsm' => '+33 6 98 76 54 32',
                'email' => 'info@conduite-moderne.fr',
                'website' => 'conduite-moderne.local',
                'subscription_plan' => 'starter',
                'is_active' => true,
                'settings' => json_encode([
                    'currency' => 'EUR',
                    'timezone' => 'Europe/Paris',
                    'language' => 'fr',
                    'driving_license_types' => ['B'],
                    'lesson_duration' => 60,
                    'max_students_per_instructor' => 6,
                    'auto_booking_enabled' => false,
                    'notification_email' => 'admin@conduite-moderne.fr',
                ]),
            ],
            [
                'name' => 'École de Conduite Pro',
                'companyName' => 'École de Conduite Pro SARL',
                'address' => '789 Boulevard de la Liberté, 13001 Marseille',
                'tel' => '+33 4 91 23 45 67',
                'gsm' => '+33 6 87 65 43 21',
                'email' => 'contact@ecole-conduite-pro.fr',
                'website' => 'ecole-conduite-pro.local',
                'subscription_plan' => 'professional',
                'is_active' => true,
                'settings' => json_encode([
                    'currency' => 'EUR',
                    'timezone' => 'Europe/Paris',
                    'language' => 'fr',
                    'driving_license_types' => ['B', 'A1', 'A2', 'A', 'C', 'D'],
                    'lesson_duration' => 60,
                    'max_students_per_instructor' => 10,
                    'auto_booking_enabled' => true,
                    'notification_email' => 'system@ecole-conduite-pro.fr',
                ]),
            ],
        ];

        foreach ($tenants as $tenantData) {
            Tenant::create($tenantData);
        }
    }
}