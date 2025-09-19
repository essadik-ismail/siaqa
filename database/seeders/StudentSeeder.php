<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Tenant;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $studentUsers = User::where('tenant_id', $tenant->id)
                ->where('name', 'like', '%Sophie%')
                ->orWhere('name', 'like', '%Thomas%')
                ->orWhere('name', 'like', '%Emma%')
                ->orWhere('name', 'like', '%Lucas%')
                ->orWhere('name', 'like', '%Chloé%')
                ->get();

            $studentData = [
                [
                    'student_number' => 'STU-' . $tenant->id . '-001',
                    'date_of_birth' => now()->subYears(20)->subMonths(3),
                    'address' => '10 Rue de la Paix, 75001 Paris',
                    'phone' => '+33 6 11 22 33 44',
                    'emergency_contact' => 'Marie Bernard',
                    'emergency_phone' => '+33 6 22 33 44 55',
                    'license_type' => 'B',
                    'status' => 'active',
                    'enrollment_date' => now()->subMonths(2),
                    'total_hours_completed' => 15,
                    'theory_exam_passed' => true,
                    'practical_exam_passed' => false,
                    'notes' => 'Élève motivée, bon niveau théorique.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-002',
                    'date_of_birth' => now()->subYears(18)->subMonths(6),
                    'address' => '20 Avenue des Ternes, 75017 Paris',
                    'phone' => '+33 6 22 33 44 55',
                    'emergency_contact' => 'Jean Petit',
                    'emergency_phone' => '+33 6 33 44 55 66',
                    'license_type' => 'B',
                    'status' => 'active',
                    'enrollment_date' => now()->subMonths(1),
                    'total_hours_completed' => 8,
                    'theory_exam_passed' => false,
                    'practical_exam_passed' => false,
                    'notes' => 'Débutant, besoin d\'accompagnement renforcé.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-003',
                    'date_of_birth' => now()->subYears(25)->subMonths(1),
                    'address' => '30 Boulevard Saint-Germain, 75005 Paris',
                    'phone' => '+33 6 33 44 55 66',
                    'emergency_contact' => 'Pierre Rousseau',
                    'emergency_phone' => '+33 6 44 55 66 77',
                    'license_type' => 'B',
                    'status' => 'active',
                    'enrollment_date' => now()->subMonths(3),
                    'total_hours_completed' => 25,
                    'theory_exam_passed' => true,
                    'practical_exam_passed' => false,
                    'notes' => 'Très bon niveau, prêt pour l\'examen pratique.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-004',
                    'date_of_birth' => now()->subYears(22)->subMonths(4),
                    'address' => '40 Rue de Rivoli, 75004 Paris',
                    'phone' => '+33 6 44 55 66 77',
                    'emergency_contact' => 'Sophie Moreau',
                    'emergency_phone' => '+33 6 55 66 77 88',
                    'license_type' => 'B',
                    'status' => 'suspended',
                    'enrollment_date' => now()->subMonths(4),
                    'total_hours_completed' => 12,
                    'theory_exam_passed' => false,
                    'practical_exam_passed' => false,
                    'notes' => 'Suspension temporaire - paiement en retard.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-005',
                    'date_of_birth' => now()->subYears(19)->subMonths(8),
                    'address' => '50 Rue de la Roquette, 75011 Paris',
                    'phone' => '+33 6 55 66 77 88',
                    'emergency_contact' => 'Claire Simon',
                    'emergency_phone' => '+33 6 66 77 88 99',
                    'license_type' => 'B',
                    'status' => 'graduated',
                    'enrollment_date' => now()->subMonths(6),
                    'total_hours_completed' => 35,
                    'theory_exam_passed' => true,
                    'practical_exam_passed' => true,
                    'notes' => 'Diplômée avec mention, excellent parcours.',
                ],
            ];

            foreach ($studentUsers as $index => $user) {
                if (isset($studentData[$index])) {
                    Student::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'student_number' => $studentData[$index]['student_number'],
                        'date_of_birth' => $studentData[$index]['date_of_birth'],
                        'address' => $studentData[$index]['address'],
                        'phone' => $studentData[$index]['phone'],
                        'emergency_contact' => $studentData[$index]['emergency_contact'],
                        'emergency_phone' => $studentData[$index]['emergency_phone'],
                        'license_type' => $studentData[$index]['license_type'],
                        'status' => $studentData[$index]['status'],
                        'enrollment_date' => $studentData[$index]['enrollment_date'],
                        'total_hours_completed' => $studentData[$index]['total_hours_completed'],
                        'theory_exam_passed' => $studentData[$index]['theory_exam_passed'],
                        'practical_exam_passed' => $studentData[$index]['practical_exam_passed'],
                        'notes' => $studentData[$index]['notes'],
                    ]);
                }
            }
        }
    }
}
