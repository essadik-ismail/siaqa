<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->words(3, true) . ' Package',
            'description' => $this->faker->paragraph,
            'license_category' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'validity_days' => $this->faker->numberBetween(30, 365),
            'theory_hours' => $this->faker->numberBetween(10, 30),
            'practical_hours' => $this->faker->numberBetween(15, 40),
            'exams_included' => $this->faker->numberBetween(1, 3),
            'max_students' => $this->faker->numberBetween(10, 50),
            'features' => $this->faker->paragraphs(3, true),
            'is_active' => $this->faker->boolean(80),
            'is_popular' => $this->faker->boolean(20),
        ];
    }
}
