<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $examDate = $this->faker->dateTimeBetween('now', '+1 month');
        $startTime = clone $examDate;
        $startTime->setTime(9, 0, 0);
        $endTime = clone $startTime;
        $endTime->modify('+2 hours');

        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'instructor_id' => Instructor::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'exam_date' => $examDate->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration' => 120, // in minutes
            'type' => $this->faker->randomElement(['theory', 'practical']),
            'status' => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'location' => $this->faker->address,
            'max_score' => 100,
            'passing_score' => 60,
            'student_score' => $this->faker->optional(0.7)->numberBetween(0, 100),
            'result' => $this->faker->optional(0.7)->randomElement(['passed', 'failed']),
            'notes' => $this->faker->optional()->paragraph,
            'completed_at' => $this->faker->optional(0.3)->dateTimeBetween($examDate, 'now'),
            'cancelled_at' => null,
        ];
    }
}
