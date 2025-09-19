<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'domain' => $this->faker->unique()->domainName,
            'database' => 'tenant_' . $this->faker->unique()->slug(2),
            'is_active' => true,
            'subscription_status' => 'active',
            'subscription_plan' => 'professional',
            'subscription_ends_at' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
