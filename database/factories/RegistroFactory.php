<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registro>
 */
class RegistroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sorteo_id' => 1,
            'nombre_apellidos' => $this->faker->lastName,
            'numero_identidad' => $this->faker->unique()->numerify('########'),
            'celular' => $this->faker->unique()->phoneNumber,
            'email' => "franklisboaabad@gmail.com",
            'monto' => rand(1,10),
            'image' => 'registros/uZmCTKAh0HiBJ1SZ7KlF9X7ldzVcXtRuSNqN2kDJ.jpg',
            'estado_registro' => 0,
        ];
    }
}
