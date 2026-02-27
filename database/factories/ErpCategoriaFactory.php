<?php

namespace Database\Factories;

use App\Models\ErpCategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ErpCategoria>
 */
class ErpCategoriaFactory extends Factory
{
    protected $model = ErpCategoria::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->words(2, true),
            'descricao' => $this->faker->optional()->sentence(),
            'ativo' => true,
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'ativo' => false,
        ]);
    }
}
