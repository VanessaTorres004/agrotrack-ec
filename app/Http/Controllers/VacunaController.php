<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\Ganado;
use App\Http\Requests\VacunaRequest;
use Illuminate\Http\Request;

class VacunaController extends Controller
{
    public function create(Ganado $ganado)
    {
        $this->authorize('update', $ganado);
        return view('vacunas.create', compact('ganado'));
    }

    public function store(VacunaRequest $request)
    {
        $validated = $request->validated();
        
        // Additional security: verify ganado ownership through finca
        $ganado = Ganado::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })->findOrFail($validated['ganado_id']);

        Vacuna::create($validated);

        return redirect()->route('ganado.index')->with('success', 'Vacuna registrada exitosamente');
    }

    public function historial(Ganado $ganado)
    {
        $vacunas = $ganado->vacunas()->orderBy('fecha_aplicacion', 'desc')->get();
        return view('vacunas.historial', compact('ganado', 'vacunas'));
    }
}

