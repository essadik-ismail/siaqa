<?php

namespace Database\Factories;

use App\Models\StudentTheoryEnrollment;
use App\Models\Student;
use App\Models\TheoryClass;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentTheoryEnrollment>
 */
class StudentTheoryEnrollmentFactory extends Factory
{
    protected $model = StudentTheoryEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enrollmentDate = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'theory_class_id' => TheoryClass::factory(),
            'enrollment_date' => $enrollmentDate->format('Y-m-d'),
            'status' => $this->faker->randomElement(['enrolled', 'completed', 'dropped']),
            'attendance_percentage' => $this->faker->numberBetween(0, 100),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
