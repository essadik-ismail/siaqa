<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Tenant;

class ExamSeeder extends Seeder
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

            $examData = [
                [
                    'student_id' => $students->first()->id ?? 1,
                    'instructor_id' => $instructors->first()->id ?? 1,
                    'exam_type' => 'theory',
                    'exam_date' => now()->addDays(7),
                    'start_time' => '09:00:00',
                    'end_time' => '10:00:00',
                    'location' => 'Centre d\'examen - Paris 15ème',
                    'status' => 'scheduled',
                    'score' => null,
                    'max_score' => 40,
                    'passing_score' => 35,
                    'notes' => 'Examen théorique - Code de la route',
                    'exam_center' => 'Centre d\'examen officiel',
                    'examiner_name' => 'M. Dubois',
                    'examiner_license' => 'EXAM-001',
                ],
                [
                    'student_id' => $students->skip(1)->first()->id ?? 1,
                    'instructor_id' => $instructors->first()->id ?? 1,
                    'exam_type' => 'practical',
                    'exam_date' => now()->addDays(14),
                    'start_time' => '14:00:00',
                    'end_time' => '15:00:00',
                    'location' => 'Centre d\'examen - Paris 15ème',
                    'status' => 'scheduled',
                    'score' => null,
                    'max_score' => 20,
                    'passing_score' => 17,
                    'notes' => 'Examen pratique - Conduite',
                    'exam_center' => 'Centre d\'examen officiel',
                    'examiner_name' => 'Mme. Martin',
                    'examiner_license' => 'EXAM-002',
                ],
                [
                    'student_id' => $students->skip(2)->first()->id ?? 1,
                    'instructor_id' => $instructors->skip(1)->first()->id ?? 1,
                    'exam_type' => 'theory',
                    'exam_date' => now()->subDays(5),
                    'start_time' => '10:00:00',
                    'end_time' => '11:00:00',
                    'location' => 'Centre d\'examen - Paris 15ème',
                    'status' => 'completed',
                    'score' => 38,
                    'max_score' => 40,
                    'passing_score' => 35,
                    'notes' => 'Examen théorique réussi avec mention',
                    'exam_center' => 'Centre d\'examen officiel',
                    'examiner_name' => 'M. Durand',
                    'examiner_license' => 'EXAM-003',
                ],
                [
                    'student_id' => $students->skip(3)->first()->id ?? 1,
                    'instructor_id' => $instructors->skip(2)->first()->id ?? 1,
                    'exam_type' => 'practical',
                    'exam_date' => now()->subDays(10),
                    'start_time' => '15:30:00',
                    'end_time' => '16:30:00',
                    'location' => 'Centre d\'examen - Paris 15ème',
                    'status' => 'failed',
                    'score' => 15,
                    'max_score' => 20,
                    'passing_score' => 17,
                    'notes' => 'Échec - Manque de confiance en conduite',
                    'exam_center' => 'Centre d\'examen officiel',
                    'examiner_name' => 'Mme. Leroy',
                    'examiner_license' => 'EXAM-004',
                ],
            ];

            foreach ($examData as $exam) {
                Exam::create([
                    'tenant_id' => $tenant->id,
                    'student_id' => $exam['student_id'],
                    'instructor_id' => $exam['instructor_id'],
                    'exam_type' => $exam['exam_type'],
                    'exam_date' => $exam['exam_date'],
                    'start_time' => $exam['start_time'],
                    'end_time' => $exam['end_time'],
                    'location' => $exam['location'],
                    'status' => $exam['status'],
                    'score' => $exam['score'],
                    'max_score' => $exam['max_score'],
                    'passing_score' => $exam['passing_score'],
                    'notes' => $exam['notes'],
                    'exam_center' => $exam['exam_center'],
                    'examiner_name' => $exam['examiner_name'],
                    'examiner_license' => $exam['examiner_license'],
                ]);
            }
        }
    }
}