<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentProgress;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Tenant;

class StudentProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $students = Student::where('tenant_id', $tenant->id)->get();
            $instructors = Instructor::where('tenant_id', $tenant->id)->get();

            foreach ($students as $student) {
                // Create progress entries for each student
                $progressData = [
                    [
                        'instructor_id' => $instructors->first()->id ?? 1,
                        'skill_category' => 'theory',
                        'skill_name' => 'Code de la route',
                        'skill_level' => 'intermediate',
                        'hours_practiced' => 2,
                        'attempts' => 3,
                        'success_rate' => 75,
                        'instructor_notes' => 'Bon niveau de compréhension, participation active',
                        'assessment_criteria' => ['Connaissance des panneaux', 'Respect des priorités', 'Compréhension des règles'],
                        'is_required' => true,
                        'is_completed' => false,
                        'last_practiced' => now()->subDays(30),
                    ],
                    [
                        'instructor_id' => $instructors->first()->id ?? 1,
                        'skill_category' => 'practical',
                        'skill_name' => 'Démarrage et freinage',
                        'skill_level' => 'advanced',
                        'hours_practiced' => 3,
                        'attempts' => 2,
                        'success_rate' => 90,
                        'instructor_notes' => 'Très bon contrôle du véhicule, calme et concentré',
                        'assessment_criteria' => ['Contrôle de l\'embrayage', 'Freinage progressif', 'Coordination'],
                        'is_required' => true,
                        'is_completed' => true,
                        'last_practiced' => now()->subDays(25),
                    ],
                    [
                        'instructor_id' => $instructors->skip(1)->first()->id ?? 1,
                        'skill_category' => 'theory',
                        'skill_name' => 'Rond-points et intersections',
                        'skill_level' => 'intermediate',
                        'hours_practiced' => 2,
                        'attempts' => 4,
                        'success_rate' => 70,
                        'instructor_notes' => 'Compréhension correcte, quelques hésitations sur les priorités',
                        'assessment_criteria' => ['Respect des priorités', 'Positionnement', 'Signalisation'],
                        'is_required' => true,
                        'is_completed' => false,
                        'last_practiced' => now()->subDays(20),
                    ],
                    [
                        'instructor_id' => $instructors->skip(1)->first()->id ?? 1,
                        'skill_category' => 'practical',
                        'skill_name' => 'Créneaux et stationnement',
                        'skill_level' => 'intermediate',
                        'hours_practiced' => 4,
                        'attempts' => 6,
                        'success_rate' => 60,
                        'instructor_notes' => 'Progrès notables, encore quelques difficultés avec les créneaux',
                        'assessment_criteria' => ['Précision', 'Positionnement', 'Contrôle de la vitesse'],
                        'is_required' => true,
                        'is_completed' => false,
                        'last_practiced' => now()->subDays(15),
                    ],
                    [
                        'instructor_id' => $instructors->skip(2)->first()->id ?? 1,
                        'skill_category' => 'practical',
                        'skill_name' => 'Conduite en ville',
                        'skill_level' => 'advanced',
                        'hours_practiced' => 5,
                        'attempts' => 3,
                        'success_rate' => 85,
                        'instructor_notes' => 'Excellente conduite, prêt pour l\'examen pratique',
                        'assessment_criteria' => ['Anticipation', 'Respect du code', 'Fluidité'],
                        'is_required' => true,
                        'is_completed' => true,
                        'last_practiced' => now()->subDays(10),
                    ],
                ];

                foreach ($progressData as $progress) {
                    StudentProgress::create([
                        'tenant_id' => $tenant->id,
                        'student_id' => $student->id,
                        'instructor_id' => $progress['instructor_id'],
                        'skill_category' => $progress['skill_category'],
                        'skill_name' => $progress['skill_name'],
                        'skill_level' => $progress['skill_level'],
                        'hours_practiced' => $progress['hours_practiced'],
                        'attempts' => $progress['attempts'],
                        'success_rate' => $progress['success_rate'],
                        'instructor_notes' => $progress['instructor_notes'],
                        'assessment_criteria' => $progress['assessment_criteria'],
                        'is_required' => $progress['is_required'],
                        'is_completed' => $progress['is_completed'],
                        'last_practiced' => $progress['last_practiced'],
                    ]);
                }
            }
        }
    }
}
