<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Vehicule;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endTime = clone $startTime;
        $endTime->modify('+2 hours');

        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'instructor_id' => Instructor::factory(),
            'vehicle_id' => Vehicule::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'lesson_date' => $startTime->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration' => 2.0,
            'type' => $this->faker->randomElement(['theory', 'practical']),
            'status' => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'location' => $this->faker->address,
            'notes' => $this->faker->optional()->paragraph,
            'completed_at' => $this->faker->optional(0.3)->dateTimeBetween($startTime, 'now'),
            'cancelled_at' => null,
        ];
    }
}
