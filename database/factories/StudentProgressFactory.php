<?php

namespace Database\Factories;

use App\Models\StudentProgress;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentProgress>
 */
class StudentProgressFactory extends Factory
{
    protected $model = StudentProgress::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'instructor_id' => Instructor::factory(),
            'lesson_id' => $this->faker->optional(0.7)->randomElement([Lesson::factory()]),
            'skill' => $this->faker->randomElement(['parking', 'highway', 'city', 'parallel', 'reversing']),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'score' => $this->faker->numberBetween(1, 10),
            'notes' => $this->faker->optional()->paragraph,
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
