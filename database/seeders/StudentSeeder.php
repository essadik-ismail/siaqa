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
                    'name' => 'Sophie Bernard',
                    'name_ar' => 'صوفي برنارد',
                    'email' => 'sophie.bernard' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                    'phone' => '+33 6 11 22 33 44',
                    'cin' => 'CIN001' . $tenant->id,
                    'birth_date' => now()->subYears(20)->subMonths(3),
                    'birth_place' => 'Paris',
                    'address' => '10 Rue de la Paix, 75001 Paris',
                    'reference' => 'REF001',
                    'cinimage' => 'cin_001.jpg',
                    'image' => 'student_001.jpg',
                    'emergency_contact_name' => 'Marie Bernard',
                    'emergency_contact_phone' => '+33 6 22 33 44 55',
                    'license_category' => 'B',
                    'status' => 'active',
                    'registration_date' => now()->subMonths(2),
                    'theory_hours_completed' => 15,
                    'practical_hours_completed' => 10,
                    'required_theory_hours' => 20,
                    'required_practical_hours' => 20,
                    'total_paid' => 800.00,
                    'total_due' => 200.00,
                    'notes' => 'Élève motivée, bon niveau théorique.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-002',
                    'name' => 'Thomas Petit',
                    'name_ar' => 'توماس بيتيت',
                    'email' => 'thomas.petit' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                    'phone' => '+33 6 22 33 44 55',
                    'cin' => 'CIN002' . $tenant->id,
                    'birth_date' => now()->subYears(18)->subMonths(6),
                    'birth_place' => 'Lyon',
                    'address' => '20 Avenue des Ternes, 75017 Paris',
                    'reference' => 'REF002',
                    'cinimage' => 'cin_002.jpg',
                    'image' => 'student_002.jpg',
                    'emergency_contact_name' => 'Jean Petit',
                    'emergency_contact_phone' => '+33 6 33 44 55 66',
                    'license_category' => 'B',
                    'status' => 'active',
                    'registration_date' => now()->subMonths(1),
                    'theory_hours_completed' => 8,
                    'practical_hours_completed' => 5,
                    'required_theory_hours' => 20,
                    'required_practical_hours' => 20,
                    'total_paid' => 400.00,
                    'total_due' => 600.00,
                    'notes' => 'Débutant, besoin d\'accompagnement renforcé.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-003',
                    'name' => 'Emma Rousseau',
                    'name_ar' => 'إيما روسو',
                    'email' => 'emma.rousseau' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                    'phone' => '+33 6 33 44 55 66',
                    'cin' => 'CIN003' . $tenant->id,
                    'birth_date' => now()->subYears(25)->subMonths(1),
                    'birth_place' => 'Marseille',
                    'address' => '30 Boulevard Saint-Germain, 75005 Paris',
                    'reference' => 'REF003',
                    'cinimage' => 'cin_003.jpg',
                    'image' => 'student_003.jpg',
                    'emergency_contact_name' => 'Pierre Rousseau',
                    'emergency_contact_phone' => '+33 6 44 55 66 77',
                    'license_category' => 'B',
                    'status' => 'active',
                    'registration_date' => now()->subMonths(3),
                    'theory_hours_completed' => 25,
                    'practical_hours_completed' => 18,
                    'required_theory_hours' => 20,
                    'required_practical_hours' => 20,
                    'total_paid' => 1200.00,
                    'total_due' => 0.00,
                    'notes' => 'Très bon niveau, prêt pour l\'examen pratique.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-004',
                    'name' => 'Lucas Moreau',
                    'name_ar' => 'لوكاس مورو',
                    'email' => 'lucas.moreau' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                    'phone' => '+33 6 44 55 66 77',
                    'cin' => 'CIN004' . $tenant->id,
                    'birth_date' => now()->subYears(22)->subMonths(4),
                    'birth_place' => 'Toulouse',
                    'address' => '40 Rue de Rivoli, 75004 Paris',
                    'reference' => 'REF004',
                    'cinimage' => 'cin_004.jpg',
                    'image' => 'student_004.jpg',
                    'emergency_contact_name' => 'Sophie Moreau',
                    'emergency_contact_phone' => '+33 6 55 66 77 88',
                    'license_category' => 'B',
                    'status' => 'suspended',
                    'registration_date' => now()->subMonths(4),
                    'theory_hours_completed' => 12,
                    'practical_hours_completed' => 8,
                    'required_theory_hours' => 20,
                    'required_practical_hours' => 20,
                    'total_paid' => 600.00,
                    'total_due' => 800.00,
                    'notes' => 'Suspension temporaire - paiement en retard.',
                ],
                [
                    'student_number' => 'STU-' . $tenant->id . '-005',
                    'name' => 'Chloé Simon',
                    'name_ar' => 'كلوي سيمون',
                    'email' => 'chloe.simon' . $tenant->id . '@' . strtolower(str_replace(' ', '-', $tenant->name)) . '.fr',
                    'phone' => '+33 6 55 66 77 88',
                    'cin' => 'CIN005' . $tenant->id,
                    'birth_date' => now()->subYears(19)->subMonths(8),
                    'birth_place' => 'Nice',
                    'address' => '50 Rue de la Roquette, 75011 Paris',
                    'reference' => 'REF005',
                    'cinimage' => 'cin_005.jpg',
                    'image' => 'student_005.jpg',
                    'emergency_contact_name' => 'Claire Simon',
                    'emergency_contact_phone' => '+33 6 66 77 88 99',
                    'license_category' => 'B',
                    'status' => 'graduated',
                    'registration_date' => now()->subMonths(6),
                    'theory_hours_completed' => 35,
                    'practical_hours_completed' => 30,
                    'required_theory_hours' => 20,
                    'required_practical_hours' => 20,
                    'total_paid' => 2000.00,
                    'total_due' => 0.00,
                    'notes' => 'Diplômée avec mention, excellent parcours.',
                ],
            ];

            foreach ($studentUsers as $index => $user) {
                if (isset($studentData[$index])) {
                    Student::create([
                        'tenant_id' => $tenant->id,
                        'user_id' => $user->id,
                        'student_number' => $studentData[$index]['student_number'],
                        'name' => $studentData[$index]['name'],
                        'name_ar' => $studentData[$index]['name_ar'],
                        'email' => $studentData[$index]['email'],
                        'phone' => $studentData[$index]['phone'],
                        'cin' => $studentData[$index]['cin'],
                        'birth_date' => $studentData[$index]['birth_date'],
                        'birth_place' => $studentData[$index]['birth_place'],
                        'address' => $studentData[$index]['address'],
                        'reference' => $studentData[$index]['reference'],
                        'cinimage' => $studentData[$index]['cinimage'],
                        'image' => $studentData[$index]['image'],
                        'emergency_contact_name' => $studentData[$index]['emergency_contact_name'],
                        'emergency_contact_phone' => $studentData[$index]['emergency_contact_phone'],
                        'license_category' => $studentData[$index]['license_category'],
                        'status' => $studentData[$index]['status'],
                        'registration_date' => $studentData[$index]['registration_date'],
                        'theory_hours_completed' => $studentData[$index]['theory_hours_completed'],
                        'practical_hours_completed' => $studentData[$index]['practical_hours_completed'],
                        'required_theory_hours' => $studentData[$index]['required_theory_hours'],
                        'required_practical_hours' => $studentData[$index]['required_practical_hours'],
                        'total_paid' => $studentData[$index]['total_paid'],
                        'total_due' => $studentData[$index]['total_due'],
                        'notes' => $studentData[$index]['notes'],
                    ]);
                }
            }
        }
    }
}
