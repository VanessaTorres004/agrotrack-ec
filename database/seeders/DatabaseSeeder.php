<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Finca;
use App\Models\Cultivo;
use App\Models\Indicador;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador AgroTrack',
            'email' => 'admin@agrotrack.ec',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cedula' => '1234567890',
            'telefono' => '0999999999',
        ]);

        // Crear usuario productor de ejemplo
        $productor = User::create([
            'name' => 'Juan Pérez',
            'email' => 'productor@example.com',
            'password' => Hash::make('password'),
            'role' => 'productor',
            'cedula' => '0987654321',
            'telefono' => '0988888888',
        ]);

        // Crear finca
        $finca = Finca::create([
            'user_id' => $productor->id,
            'nombre' => 'Finca El Paraíso',
            'ubicacion' => 'Km 25 Vía Guayaquil-Daule',
            'area_total' => 50.5,
            'provincia' => 'Guayas',
            'canton' => 'Daule',
            'descripcion' => 'Finca agrícola dedicada al cultivo de maíz y banano',
        ]);

        // Crear cultivos
        $cultivos = [
            [
                'nombre' => 'Maíz',
                'variedad' => 'Amarillo Duro',
                'area' => 20.0,
                'fecha_siembra' => now()->subMonths(3),
            ],
            [
                'nombre' => 'Banano',
                'variedad' => 'Cavendish',
                'area' => 15.5,
                'fecha_siembra' => now()->subMonths(8),
            ],
            [
                'nombre' => 'Cacao',
                'variedad' => 'CCN-51',
                'area' => 15.0,
                'fecha_siembra' => now()->subYear(),
            ],
        ];

        foreach ($cultivos as $cultivoData) {
            $cultivo = Cultivo::create([
                'finca_id' => $finca->id,
                'nombre' => $cultivoData['nombre'],
                'variedad' => $cultivoData['variedad'],
                'area' => $cultivoData['area'],
                'fecha_siembra' => $cultivoData['fecha_siembra'],
                'fecha_cosecha_estimada' => $cultivoData['fecha_siembra']->copy()->addMonths(6),
                'estado' => 'activo',
            ]);

            // Calcular IDC inicial
            Indicador::calcularIDC($cultivo->id);
        }
    }
}
