<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tipo_documento' => $this->faker->randomElement(['DNI', 'RUC', 'Carnet de extranjería']),
            'documento' => $this->faker->unique()->numerify('##########'),
            'nombre_comercial' => $this->faker->company,
            'razon_social' => $this->faker->company,
            'direccion' => $this->faker->address,
            'departamento' => $this->faker->word,
            'provincia' => $this->faker->word,
            'distrito' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail,
            'celular' => $this->faker->phoneNumber,
            'estado' => $this->faker->randomElement([true, false]),
        ];
    }
}
