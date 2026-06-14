<?php

namespace Database\Seeders;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Administrador SERPOST', 'email' => 'admin@demo.pe', 'rol' => RolUsuario::ADMIN->value],
            ['name' => 'Asesora Ventanilla', 'email' => 'ventanilla@demo.pe', 'rol' => RolUsuario::VENTANILLA->value],
            ['name' => 'José Escuadra Canales', 'email' => 'cliente@demo.pe', 'rol' => RolUsuario::CLIENTE->value, 'numero_documento' => '40123456', 'telefono' => '905456589'],
        ];

        foreach ($usuarios as $datos) {
            User::updateOrCreate(
                ['email' => $datos['email']],
                [...$datos, 'password' => Hash::make('demo1234')],
            );
        }
    }
}
