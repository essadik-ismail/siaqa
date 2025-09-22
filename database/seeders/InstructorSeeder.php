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
                    'employee_number' => 'EMP-' . $tenant->id . '-001',
                    'license_number' => 'INSTR-' . $tenant->id . '-001',
                    'license_expiry' => now()->addYears(5),
                    'license_categories' => 'B',
                    'years_experience' => 10,
                    'specializations' => 'conduite, code',
                    'hourly_rate' => 35.00,
                    'max_students' => 8,
                    'current_students' => 5,
                    'status' => 'active',
                    'is_available' => true,
                    'availability_schedule' => [
                        'monday' => ['09:00-12:00', '14:00-18:00'],
                        'tuesday' => ['09:00-12:00', '14:00-18:00'],
                        'wednesday' => ['09:00-12:00', '14:00-18:00'],
                        'thursday' => ['09:00-12:00', '14:00-18:00'],
                        'friday' => ['09:00-12:00', '14:00-18:00'],
                        'saturday' => ['09:00-12:00'],
                        'sunday' => []
                    ],
                    'notes' => 'Instructeur expérimenté avec plus de 10 ans d\'expérience dans l\'enseignement de la conduite.',
                ],
                [
                    'employee_number' => 'EMP-' . $tenant->id . '-002',
                    'license_number' => 'INSTR-' . $tenant->id . '-002',
                    'license_expiry' => now()->addYears(7),
                    'license_categories' => 'B',
                    'years_experience' => 5,
                    'specializations' => 'conduite, perfectionnement',
                    'hourly_rate' => 32.00,
                    'max_students' => 6,
                    'current_students' => 4,
                    'status' => 'active',
                    'is_available' => true,
                    'availability_schedule' => [
                        'monday' => ['08:00-12:00', '13:00-17:00'],
                        'tuesday' => ['08:00-12:00', '13:00-17:00'],
                        'wednesday' => ['08:00-12:00', '13:00-17:00'],
                        'thursday' => ['08:00-12:00', '13:00-17:00'],
                        'friday' => ['08:00-12:00', '13:00-17:00'],
                        'saturday' => ['08:00-12:00'],
                        'sunday' => []
                    ],
                    'notes' => 'Spécialisée dans l\'accompagnement des jeunes conducteurs et la conduite défensive.',
                ],
                [
                    'employee_number' => 'EMP-' . $tenant->id . '-003',
                    'license_number' => 'INSTR-' . $tenant->id . '-003',
                    'license_expiry' => now()->addYears(2),
                    'license_categories' => 'B, A',
                    'years_experience' => 15,
                    'specializations' => 'conduite, code, moto',
                    'hourly_rate' => 38.00,
                    'max_students' => 10,
                    'current_students' => 8,
                    'status' => 'active',
                    'is_available' => false,
                    'availability_schedule' => [
                        'monday' => ['09:00-12:00', '14:00-19:00'],
                        'tuesday' => ['09:00-12:00', '14:00-19:00'],
                        'wednesday' => ['09:00-12:00', '14:00-19:00'],
                        'thursday' => ['09:00-12:00', '14:00-19:00'],
                        'friday' => ['09:00-12:00', '14:00-19:00'],
                        'saturday' => ['09:00-15:00'],
                        'sunday' => []
                    ],
                    'notes' => 'Instructeur polyvalent, formé à tous types de véhicules et techniques d\'apprentissage.',
                ],
            ];

            foreach ($instructorUsers as $index => $user) {
                if (isset($instructorData[$index])) {
                    Instructor::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'employee_number' => $instructorData[$index]['employee_number'],
                        'license_number' => $instructorData[$index]['license_number'],
                        'license_expiry' => $instructorData[$index]['license_expiry'],
                        'license_categories' => $instructorData[$index]['license_categories'],
                        'years_experience' => $instructorData[$index]['years_experience'],
                        'specializations' => $instructorData[$index]['specializations'],
                        'hourly_rate' => $instructorData[$index]['hourly_rate'],
                        'max_students' => $instructorData[$index]['max_students'],
                        'current_students' => $instructorData[$index]['current_students'],
                        'status' => $instructorData[$index]['status'],
                        'is_available' => $instructorData[$index]['is_available'],
                        'availability_schedule' => $instructorData[$index]['availability_schedule'],
                        'notes' => $instructorData[$index]['notes'],
                    ]);
                }
            }
        }
    }
}
