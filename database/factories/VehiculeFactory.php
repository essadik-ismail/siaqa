<?php

namespace Database\Factories;

use App\Models\Vehicule;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicule>
 */
class VehiculeFactory extends Factory
{
    protected $model = Vehicule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'marque' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Nissan', 'Hyundai']),
            'modele' => $this->faker->word,
            'annee' => $this->faker->numberBetween(2015, 2024),
            'immatriculation' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}[A-Z]{2}'),
            'couleur' => $this->faker->colorName,
            'type' => $this->faker->randomElement(['voiture', 'moto', 'camion', 'bus']),
            'status' => $this->faker->randomElement(['available', 'in_use', 'maintenance', 'out_of_service']),
            'kilometrage' => $this->faker->numberBetween(0, 200000),
            'carburant' => $this->faker->randomElement(['essence', 'diesel', 'hybride', 'electrique']),
            'transmission' => $this->faker->randomElement(['manuelle', 'automatique']),
            'nombre_places' => $this->faker->numberBetween(2, 8),
            'prix_journalier' => $this->faker->randomFloat(2, 100, 500),
            'assurance_expire' => $this->faker->dateTimeBetween('now', '+1 year'),
            'visite_technique_expire' => $this->faker->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->optional()->paragraph,
        ];
    }
}
