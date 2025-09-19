<?php

namespace Database\Factories;

use App\Models\VehicleAssignment;
use App\Models\Lesson;
use App\Models\Instructor;
use App\Models\Vehicule;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleAssignment>
 */
class VehicleAssignmentFactory extends Factory
{
    protected $model = VehicleAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignmentDate = $this->faker->dateTimeBetween('now', '+1 month');
        $startTime = clone $assignmentDate;
        $startTime->setTime(9, 0, 0);
        $endTime = clone $startTime;
        $endTime->modify('+2 hours');

        return [
            'tenant_id' => Tenant::factory(),
            'lesson_id' => Lesson::factory(),
            'instructor_id' => Instructor::factory(),
            'vehicle_id' => Vehicule::factory(),
            'assignment_date' => $assignmentDate->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'status' => $this->faker->randomElement(['assigned', 'in_use', 'completed', 'cancelled']),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
