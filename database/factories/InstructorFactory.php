<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

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
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'cin' => $this->faker->unique()->regexify('[A-Z]{1}[0-9]{6}'),
            'birth_date' => $this->faker->date('Y-m-d', '1980-01-01'),
            'address' => $this->faker->address,
            'license_categories' => $this->faker->randomElements(['A', 'B', 'C', 'D'], 2),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended', 'on_leave']),
            'experience_years' => $this->faker->numberBetween(1, 20),
            'hourly_rate' => $this->faker->randomFloat(2, 30, 100),
            'is_available' => $this->faker->boolean(80),
            'specialties' => $this->faker->randomElements(['defensive_driving', 'highway', 'city', 'parallel_parking'], 2),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
