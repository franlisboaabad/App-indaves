<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Serie;
use App\Models\Artista;
use App\Models\Cliente;
use App\Models\Customer;
use App\Models\Invitado;
use App\Models\MetodoPago;
use App\Models\TipoEquipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RoleSeeder::class);


        \App\Models\User::factory()->create(
            [
                'name' => 'Administrador',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('secret'), // password
            ]
        )->assignRole('Admin');

        //Empresa default
        \App\Models\Empresa::factory()->create([
            'name' => 'Empresa Ejemplo',
            'address' => '1234 Calle Principal',
            'phone' => '555-1234',
            'email' => 'info@empresa.com',
            'website' => 'https://www.empresa.com',
            'description' => 'Descripción de la empresa de ejemplo.',
            'status' => true,
        ]);

        //Cliente por defecto
        \App\Models\Cliente::factory()->create([
            'tipo_documento' => 0,
            'documento' => '99999999',
            'nombre_comercial' => 'CLIENTE VARIOS',
            'razon_social' => 'CLIENTE VARIOS',
            'estado' => true,
        ]);

        //series
        $dataSeries = [
            ['number' => 'NV01', 'serie' => '1'],
            ['number' => 'OD01', 'serie' => '1'],
        ];

        // Inserta los datos en la tabla 'series'
        DB::table('series')->insert($dataSeries);


        // Metodos de pago +
        $descripciones = ['Efectivo', 'Transferencia', 'Yape', 'Plin'];
        foreach ($descripciones as $descripcion) {
            DB::table('metodo_pagos')->insert([
                'descripcion' => $descripcion,
                // Agrega aquí otros campos si es necesario
            ]);
        }
    }
}
