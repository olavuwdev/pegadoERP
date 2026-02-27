<?php

namespace Database\Factories;

use App\Models\ErpProduto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ErpProduto>
 */
class ErpProdutoFactory extends Factory
{
    protected $model = ErpProduto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo' => $this->faker->randomElement(['PRODUTO', 'SERVICO']),
            'nome' => $this->faker->words(3, true),
            'codigo_sku' => 'SKU-' . $this->faker->unique()->numerify('######'),
            'codigo_barras' => $this->faker->optional()->ean13(),
            'descricao' => $this->faker->optional()->sentence(),
            'unidade_medida' => $this->faker->randomElement(['UN', 'CX', 'KG', 'L', 'M', 'PC']),
            'preco_custo' => $this->faker->randomFloat(4, 10, 500),
            'preco_venda' => $this->faker->randomFloat(4, 50, 1000),
            'quantidade_estoque' => $this->faker->randomFloat(4, 0, 1000),
            'estoque_minimo' => $this->faker->randomFloat(4, 1, 50),
            'ncm' => $this->faker->optional()->numerify('########'),
            'cest' => $this->faker->optional()->numerify('#######'),
            'origem_mercadoria' => $this->faker->numberBetween(0, 8),
            'ativo' => true,
            'categoria_id' => null,
            'marca_id' => null,
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'ativo' => false,
        ]);
    }

    /**
     * Indicate that the product is a service.
     */
    public function servico(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipo' => 'SERVICO',
        ]);
    }
}
