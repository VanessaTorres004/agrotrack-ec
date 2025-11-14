<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@agrotrack.ec',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cedula' => '1234567890',
            'telefono' => '0999999999',
        ]);

        User::create([
            'name' => 'Juan Pérez',
            'email' => 'productor@agrotrack.ec',
            'password' => Hash::make('password'),
            'role' => 'productor',
            'cedula' => '0987654321',
            'telefono' => '0988888888',
        ]);
    }
}
