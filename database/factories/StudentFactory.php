<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'student_number' => 'STU-' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->name,
            'name_ar' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'cin' => $this->faker->unique()->regexify('[A-Z]{1}[0-9]{6}'),
            'birth_date' => $this->faker->date('Y-m-d', '2000-01-01'),
            'birth_place' => $this->faker->city,
            'address' => $this->faker->address,
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_phone' => $this->faker->phoneNumber,
            'license_category' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'status' => $this->faker->randomElement(['registered', 'active', 'suspended', 'graduated', 'dropped']),
            'registration_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'theory_hours_completed' => $this->faker->numberBetween(0, 20),
            'practical_hours_completed' => $this->faker->numberBetween(0, 20),
            'required_theory_hours' => 20,
            'required_practical_hours' => 20,
            'total_paid' => $this->faker->randomFloat(2, 0, 5000),
            'total_due' => $this->faker->randomFloat(2, 0, 2000),
            'progress_skills' => $this->faker->randomElements(['parking', 'highway', 'city', 'parallel'], 2),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
