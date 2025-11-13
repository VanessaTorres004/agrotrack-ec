<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Maquinaria;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function create(Maquinaria $maquinaria)
    {
        $this->authorize('update', $maquinaria);
        return view('mantenimientos.create', compact('maquinaria'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'maquinaria_id' => 'required|exists:maquinaria,id',
            'fecha_mantenimiento' => 'required|date',
            'tipo' => 'required|in:preventivo,correctivo,revision',
            'descripcion' => 'required|string',
            'costo' => 'required|numeric|min:0',
            'tecnico' => 'nullable|string',
        ]);

        Mantenimiento::create($validated);

        // Actualizar fecha de último servicio
        $maquinaria = Maquinaria::find($validated['maquinaria_id']);
        $maquinaria->update([
            'fecha_ultimo_servicio' => $validated['fecha_mantenimiento'],
            'estado' => 'operativa',
        ]);

        return redirect()->route('maquinaria.index')->with('success', 'Mantenimiento registrado exitosamente');
    }

    public function historial(Maquinaria $maquinaria)
    {
        $mantenimientos = $maquinaria->mantenimientos()->orderBy('fecha_mantenimiento', 'desc')->get();
        return view('mantenimientos.historial', compact('maquinaria', 'mantenimientos'));
    }
}
