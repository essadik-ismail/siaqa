<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Tenant;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $instructorUsers = User::where('tenant_id', $tenant->id)
                ->where('name', 'like', '%Jean%')
                ->orWhere('name', 'like', '%Marie%')
                ->orWhere('name', 'like', '%Pierre%')
                ->get();

            $instructorData = [
                [
                    'license_number' => 'INSTR-' . $tenant->id . '-001',
                    'license_type' => 'B',
                    'license_issue_date' => now()->subYears(5),
                    'license_expiry_date' => now()->addYears(5),
                    'specializations' => ['conduite', 'code'],
                    'hourly_rate' => 35.00,
                    'max_students' => 8,
                    'is_available' => true,
                    'bio' => 'Instructeur expérimenté avec plus de 10 ans d\'expérience dans l\'enseignement de la conduite.',
                    'languages' => ['fr', 'en'],
                ],
                [
                    'license_number' => 'INSTR-' . $tenant->id . '-002',
                    'license_type' => 'B',
                    'license_issue_date' => now()->subYears(3),
                    'license_expiry_date' => now()->addYears(7),
                    'specializations' => ['conduite', 'perfectionnement'],
                    'hourly_rate' => 32.00,
                    'max_students' => 6,
                    'is_available' => true,
                    'bio' => 'Spécialisée dans l\'accompagnement des jeunes conducteurs et la conduite défensive.',
                    'languages' => ['fr'],
                ],
                [
                    'license_number' => 'INSTR-' . $tenant->id . '-003',
                    'license_type' => 'B',
                    'license_issue_date' => now()->subYears(8),
                    'license_expiry_date' => now()->addYears(2),
                    'specializations' => ['conduite', 'code', 'moto'],
                    'hourly_rate' => 38.00,
                    'max_students' => 10,
                    'is_available' => false,
                    'bio' => 'Instructeur polyvalent, formé à tous types de véhicules et techniques d\'apprentissage.',
                    'languages' => ['fr', 'es'],
                ],
            ];

            foreach ($instructorUsers as $index => $user) {
                if (isset($instructorData[$index])) {
                    Instructor::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'license_number' => $instructorData[$index]['license_number'],
                        'license_type' => $instructorData[$index]['license_type'],
                        'license_issue_date' => $instructorData[$index]['license_issue_date'],
                        'license_expiry_date' => $instructorData[$index]['license_expiry_date'],
                        'specializations' => $instructorData[$index]['specializations'],
                        'hourly_rate' => $instructorData[$index]['hourly_rate'],
                        'max_students' => $instructorData[$index]['max_students'],
                        'is_available' => $instructorData[$index]['is_available'],
                        'bio' => $instructorData[$index]['bio'],
                        'languages' => $instructorData[$index]['languages'],
                    ]);
                }
            }
        }
    }
}
