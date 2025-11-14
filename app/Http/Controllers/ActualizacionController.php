<?php

namespace App\Http\Controllers;

use App\Models\Actualizacion;
use App\Models\Cultivo;
use Illuminate\Http\Request;

class ActualizacionController extends Controller
{
    public function index()
    {
        $actualizaciones = Actualizacion::whereHas('cultivo.finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('cultivo.finca')
        ->latest()
        ->paginate(20);

        return view('actualizaciones.index', compact('actualizaciones'));
    }

    public function create()
    {
        $cultivos = Cultivo::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return view('actualizaciones.create', compact('cultivos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cultivo_id' => 'required|exists:cultivos,id',
            'tipo_actividad' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha_actividad' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
        ]);

        Actualizacion::create($validated);

        return redirect()->route('actualizaciones.index')
            ->with('success', 'Actualización registrada exitosamente');
    }

    public function show(Actualizacion $actualizacione)
    {
        $this->authorize('view', $actualizacione);
        return view('actualizaciones.show', compact('actualizacione'));
    }

    public function edit(Actualizacion $actualizacione)
    {
        $this->authorize('update', $actualizacione);
        
        $cultivos = Cultivo::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return view('actualizaciones.edit', compact('actualizacione', 'cultivos'));
    }

    public function update(Request $request, Actualizacion $actualizacione)
    {
        $this->authorize('update', $actualizacione);

        $validated = $request->validate([
            'cultivo_id' => 'required|exists:cultivos,id',
            'tipo_actividad' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'fecha_actividad' => 'required|date',
            'costo' => 'nullable|numeric|min:0',
        ]);

        $actualizacione->update($validated);

        return redirect()->route('actualizaciones.index')
            ->with('success', 'Actualización modificada exitosamente');
    }

    public function destroy(Actualizacion $actualizacione)
    {
        $this->authorize('delete', $actualizacione);
        $actualizacione->delete();

        return redirect()->route('actualizaciones.index')
            ->with('success', 'Actualización eliminada exitosamente');
    }
}
