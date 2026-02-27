<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPJ = $this->faker->boolean(70);

        return [
            'tipo' => $this->faker->randomElement(['CLIENTE', 'FORNECEDOR', 'AMBOS']),
            'tipo_pessoa' => $isPJ ? 'J' : 'F',
            'nome_fantasia' => $this->faker->company(),
            'razao_social' => $isPJ ? $this->faker->company() . ' LTDA' : $this->faker->name(),
            'cnpj_cpf' => $isPJ
                ? $this->faker->unique()->numerify('##.###.###/####-##')
                : $this->faker->unique()->numerify('###.###.###-##'),
            'email' => $this->faker->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
        ];
    }

    /**
     * Indicate that the client is a supplier.
     */
    public function fornecedor(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipo' => 'FORNECEDOR',
        ]);
    }

    /**
     * Indicate that the client is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'ativo' => false,
        ]);
    }
}
