<?php

namespace Nfgarching\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nfgarching\Core\Models\Prop;

/**
 * @extends Factory<Prop>
 */
class PropFactory extends Factory
{
    protected $model = Prop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => 'App\Models\User',
            'model_id' => 1,
            'key' => $this->faker->optional()->word(),
            'state' => $this->faker->optional()->randomElement(['active', 'error', 'verified', 'unverified', 'pending']),
            'type' => $this->faker->optional()->randomElement(['birth_date', 'death_date', 'iban', 'metadata']),
            'label' => $this->faker->optional()->word(),
            'value' => $this->faker->optional()->word(),
            'data' => null,
            'memo' => $this->faker->optional()->sentence(),
        ];
    }
}
