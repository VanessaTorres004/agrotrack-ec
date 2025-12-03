<?php

namespace App\Http\Controllers;

use App\Models\RegistroClimatico;
use App\Models\Finca;
use App\Models\Indicador;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistroClimaticoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $registros = RegistroClimatico::with('finca')
                ->orderByDesc('fecha')
                ->paginate(30);
        } else {
            $registros = RegistroClimatico::whereHas('finca', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->with('finca')
                ->orderByDesc('fecha')
                ->paginate(30);
        }

        return view('clima.index', compact('registros'));
    }

    public function create()
    {
        $user = auth()->user();

        $fincas = $user->isAdmin()
            ? Finca::all()
            : Finca::where('user_id', auth()->id())->get();

        return view('clima.create', compact('fincas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'finca_id'        => 'required|exists:fincas,id',
            'fecha'           => 'required|date',
            'temperatura_min' => 'nullable|numeric',
            'temperatura_max' => 'nullable|numeric',
            'humedad'         => 'nullable|numeric|min:0|max:100',
            'precipitacion'   => 'nullable|numeric|min:0',
            'eventos'         => 'nullable|string|max:255',
            'factor_clima'    => 'nullable|numeric|min:0.5|max:1.5',
        ]);

        // Validar acceso
        Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->orWhere(auth()->user()->isAdmin())
            ->firstOrFail();

        // Si hay evento climático → aplicar factor automáticamente
        if (!empty($validated['eventos'])) {
            $eventos = strtolower($validated['eventos']);

            if (str_contains($eventos, 'helada') ||
                str_contains($eventos, 'granizo') ||
                str_contains($eventos, 'sequía') ||
                str_contains($eventos, 'seca') ||
                str_contains($eventos, 'inundación') ||
                str_contains($eventos, 'viento') ||
                str_contains($eventos, 'tormenta')) 
            {
                $validated['factor_clima'] = 0.95;
            }
        }

        // Valor por defecto si no se define
        if (!isset($validated['factor_clima'])) {
            $validated['factor_clima'] = 1.00;
        }

        // Registrar clima
        $registro = RegistroClimatico::create($validated);

        // Recalcular IDC de todos los cultivos de la finca
        foreach ($registro->finca->cultivos as $cultivo) {
            Indicador::calcularIDC($cultivo->id);
        }

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático añadido y IDC actualizado');
    }

    public function show(RegistroClimatico $clima)
    {
        $this->authorize('view', $clima);

        return view('clima.show', compact('clima'));
    }

    public function edit(RegistroClimatico $clima)
    {
        $this->authorize('update', $clima);

        $user = auth()->user();

        $fincas = $user->isAdmin()
            ? Finca::all()
            : Finca::where('user_id', auth()->id())->get();

        return view('clima.edit', compact('clima', 'fincas'));
    }

    public function update(Request $request, RegistroClimatico $clima)
    {
        $this->authorize('update', $clima);

        $validated = $request->validate([
            'finca_id'        => 'required|exists:fincas,id',
            'fecha'           => 'required|date',
            'temperatura_min' => 'nullable|numeric',
            'temperatura_max' => 'nullable|numeric',
            'humedad'         => 'nullable|numeric|min:0|max:100',
            'precipitacion'   => 'nullable|numeric|min:0',
            'eventos'         => 'nullable|string|max:255',
            'factor_clima'    => 'nullable|numeric|min:0.5|max:1.5',
        ]);

        // Reaplicar lógica de eventos adversos
        if (!empty($validated['eventos'])) {
            $eventos = strtolower($validated['eventos']);

            if (str_contains($eventos, 'helada') ||
                str_contains($eventos, 'granizo') ||
                str_contains($eventos, 'sequía') ||
                str_contains($eventos, 'inundación') ||
                str_contains($eventos, 'viento')) 
            {
                $validated['factor_clima'] = 0.95;
            }
        }

        if (!isset($validated['factor_clima'])) {
            $validated['factor_clima'] = 1.00;
        }

        $clima->update($validated);

        // Recalcular IDC de cultivos afectados
        foreach ($clima->finca->cultivos as $cultivo) {
            Indicador::calcularIDC($cultivo->id);
        }

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático actualizado');
    }

    public function destroy(RegistroClimatico $clima)
    {
        $this->authorize('delete', $clima);

        $finca = $clima->finca;
        $clima->delete();

        // Recalcular IDC afectado por eliminación
        foreach ($finca->cultivos as $cultivo) {
            Indicador::calcularIDC($cultivo->id);
        }

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático eliminado');
    }
}
