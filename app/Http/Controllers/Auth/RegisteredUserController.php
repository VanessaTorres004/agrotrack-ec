<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Finca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'telefono'  => ['nullable', 'string', 'max:20'],
            'cedula'    => ['nullable', 'string', 'max:20'],
            'role'      => ['required', 'in:admin,productor']
        ]);

        // 2. CREAR USUARIO
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'telefono'  => $validated['telefono'] ?? null,
            'cedula'    => $validated['cedula'] ?? null,
            'role'      => $validated['role']
        ]);

        // 3. SI ES PRODUCTOR → CREAR FINCA AUTOMÁTICA
        if ($user->role === 'productor') {
            Finca::create([
                'user_id'   => $user->id,
                'nombre'    => "Finca de " . $user->name,
                'ubicacion' => "No especificada",
                'area_total'=> 1.00,
                'provincia' => null,
                'canton'    => null,
                'descripcion' => "Finca creada automáticamente para el productor."
            ]);
        }

        // 4. INICIAR SESIÓN AUTOMÁTICAMENTE
        auth()->login($user);

        // 5. REDIRECCIONAR SEGÚN ROL
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
}
