<?php

namespace Database\Factories;

use App\Models\ErpMarca;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ErpMarca>
 */
class ErpMarcaFactory extends Factory
{
    protected $model = ErpMarca::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->company(),
            'ativo' => true,
        ];
    }

    /**
     * Indicate that the brand is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'ativo' => false,
        ]);
    }
}
