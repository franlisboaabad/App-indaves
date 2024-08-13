<?php

namespace Database\Seeders;

use App\Models\Artista;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Customer;
use App\Models\Invitado;
use App\Models\TipoEquipo;
use Illuminate\Database\Seeder;
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
                'password' => bcrypt('triki123***@'), // password
            ]
        )->assignRole('Admin');

        // Invitado::factory()->count(30)->create();
        // User::factory()->count(10)->create();
        // Cliente::factory()->count(10)->create();
        // TipoEquipo::factory()->count(10)->create();
        // Artista::factory()->count(10)->create();
    }
}
