<?php

namespace Database\Factories;

use App\Models\StudentPackage;
use App\Models\Student;
use App\Models\Package;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentPackage>
 */
class StudentPackageFactory extends Factory
{
    protected $model = StudentPackage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enrollmentDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $expiryDate = clone $enrollmentDate;
        $expiryDate->modify('+1 year');

        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'package_id' => Package::factory(),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'enrollment_date' => $enrollmentDate->format('Y-m-d'),
            'expiry_date' => $expiryDate->format('Y-m-d'),
            'status' => $this->faker->randomElement(['active', 'completed', 'expired', 'cancelled']),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
