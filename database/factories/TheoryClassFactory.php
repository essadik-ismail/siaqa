<?php

namespace Database\Factories;

use App\Models\TheoryClass;
use App\Models\Instructor;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TheoryClass>
 */
class TheoryClassFactory extends Factory
{
    protected $model = TheoryClass::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $classDate = $this->faker->dateTimeBetween('now', '+1 month');
        $startTime = clone $classDate;
        $startTime->setTime(9, 0, 0);
        $endTime = clone $startTime;
        $endTime->modify('+2 hours');

        return [
            'tenant_id' => Tenant::factory(),
            'instructor_id' => Instructor::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'class_date' => $classDate->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'max_students' => $this->faker->numberBetween(10, 30),
            'current_students' => $this->faker->numberBetween(0, 25),
            'status' => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'location' => $this->faker->address,
            'notes' => $this->faker->optional()->paragraph,
        ];
    }
}
