<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Vehicule;
use App\Models\Tenant;

class LessonSeeder extends Seeder
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
            $vehicles = Vehicule::where('tenant_id', $tenant->id)->where('statut', 'disponible')->get();

            if ($students->isEmpty() || $instructors->isEmpty() || $vehicles->isEmpty()) {
                continue;
            }

            $lessonTypes = ['theory', 'practical', 'simulation'];
            $statuses = ['scheduled', 'completed', 'cancelled'];
            $skills = [
                'Démarrage et arrêt',
                'Changement de vitesse',
                'Créneaux',
                'Rond-point',
                'Autoroute',
                'Stationnement',
                'Conduite en ville',
                'Conduite de nuit',
            ];

            // Create lessons for the past 30 days
            for ($i = 0; $i < 50; $i++) {
                $student = $students->random();
                $instructor = $instructors->random();
                $vehicle = $vehicles->random();
                $lessonType = $lessonTypes[array_rand($lessonTypes)];
                $status = $statuses[array_rand($statuses)];
                
                $scheduledAt = now()->subDays(rand(0, 30))->setHour(rand(8, 18))->setMinute(0);
                $completedAt = $status === 'completed' ? $scheduledAt->copy()->addMinutes(60) : null;
                
                $skillsCovered = $lessonType === 'practical' ? 
                    array_slice($skills, 0, rand(1, 3)) : 
                    ['Code de la route', 'Signalisation', 'Priorités'];

                Lesson::create([
                    'tenant_id' => $tenant->id,
                    'student_id' => $student->id,
                    'instructor_id' => $instructor->id,
                    'vehicle_id' => $lessonType === 'practical' ? $vehicle->id : null,
                    'lesson_number' => 'LESS-' . $tenant->id . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'lesson_type' => $lessonType,
                    'title' => $this->generateLessonTitle($lessonType),
                    'description' => $this->generateLessonDescription($lessonType),
                    'scheduled_at' => $scheduledAt,
                    'completed_at' => $completedAt,
                    'duration_minutes' => 60,
                    'status' => $status,
                    'location' => $lessonType === 'theory' ? 'Salle de cours' : 'Parcours pratique',
                    'price' => $lessonType === 'theory' ? 25.00 : 35.00,
                    'skills_covered' => $skillsCovered,
                    'student_rating' => $status === 'completed' ? rand(3, 5) : null,
                    'instructor_notes' => $status === 'completed' ? $this->generateInstructorNotes() : null,
                    'student_feedback' => $status === 'completed' ? $this->generateStudentFeedback() : null,
                    'cancellation_reason' => $status === 'cancelled' ? $this->generateCancellationReason() : null,
                ]);
            }
        }
    }

    private function generateLessonTitle($type)
    {
        $titles = [
            'theory' => [
                'Code de la route - Signalisation',
                'Règles de priorité',
                'Conduite écologique',
                'Sécurité routière',
            ],
            'practical' => [
                'Première leçon de conduite',
                'Perfectionnement créneaux',
                'Conduite sur autoroute',
                'Révision générale',
            ],
            'simulation' => [
                'Simulation conditions difficiles',
                'Simulation conduite de nuit',
                'Simulation situations d\'urgence',
            ],
        ];

        return $titles[$type][array_rand($titles[$type])];
    }

    private function generateLessonDescription($type)
    {
        $descriptions = [
            'theory' => 'Cours théorique sur les règles de circulation et la signalisation routière.',
            'practical' => 'Leçon pratique de conduite avec mise en application des techniques apprises.',
            'simulation' => 'Session de simulation pour préparer aux situations difficiles de conduite.',
        ];

        return $descriptions[$type];
    }

    private function generateInstructorNotes()
    {
        $notes = [
            'Très bon niveau, progression rapide.',
            'Attention particulière sur les priorités à droite.',
            'Excellent contrôle du véhicule.',
            'Bien, continuez comme ça.',
            'Quelques difficultés avec les créneaux, à revoir.',
        ];

        return $notes[array_rand($notes)];
    }

    private function generateStudentFeedback()
    {
        $feedbacks = [
            'Très bon cours, instructeur patient.',
            'Explications claires et utiles.',
            'Bonne ambiance, j\'apprends bien.',
            'Cours adapté à mon niveau.',
            'Très satisfait de cette leçon.',
        ];

        return $feedbacks[array_rand($feedbacks)];
    }

    private function generateCancellationReason()
    {
        $reasons = [
            'Maladie de l\'élève',
            'Problème technique véhicule',
            'Météo défavorable',
            'Urgence personnelle',
            'Conflit d\'horaire',
        ];

        return $reasons[array_rand($reasons)];
    }
}
