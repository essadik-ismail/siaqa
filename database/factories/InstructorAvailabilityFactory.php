<?php

namespace Database\Factories;

use App\Models\InstructorAvailability;
use App\Models\Instructor;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstructorAvailability>
 */
class InstructorAvailabilityFactory extends Factory
{
    protected $model = InstructorAvailability::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endTime = clone $startTime;
        $endTime->modify('+8 hours');

        return [
            'tenant_id' => Tenant::factory(),
            'instructor_id' => Instructor::factory(),
            'date' => $startTime->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'is_available' => $this->faker->boolean(80),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
