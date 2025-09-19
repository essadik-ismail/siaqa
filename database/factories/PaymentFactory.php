<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Exam;
use App\Models\Package;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = clone $paymentDate;
        $dueDate->modify('+30 days');

        return [
            'tenant_id' => Tenant::factory(),
            'student_id' => Student::factory(),
            'lesson_id' => $this->faker->optional(0.3)->randomElement([Lesson::factory()]),
            'exam_id' => $this->faker->optional(0.2)->randomElement([Exam::factory()]),
            'package_id' => $this->faker->optional(0.5)->randomElement([Package::factory()]),
            'amount' => $this->faker->randomFloat(2, 50, 1000),
            'currency' => 'MAD',
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer', 'credit_card']),
            'status' => $this->faker->randomElement(['pending', 'paid', 'failed', 'cancelled']),
            'payment_date' => $paymentDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'description' => $this->faker->sentence,
            'reference' => 'PAY-' . $this->faker->unique()->numberBetween(1000, 9999),
            'notes' => $this->faker->optional()->paragraph,
            'processed_at' => $this->faker->optional(0.7)->dateTimeBetween($paymentDate, 'now'),
            'cancelled_at' => null,
        ];
    }
}
