<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\Finca;
use App\Http\Requests\MaquinariaRequest;
use Illuminate\Http\Request;

class MaquinariaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->rol === 'administrador') {
            $maquinaria = Maquinaria::with(['finca', 'mantenimientos'])->get();
        } else {
            $fincas = $user->fincas->pluck('id');
            $maquinaria = Maquinaria::with(['finca', 'mantenimientos'])->whereIn('finca_id', $fincas)->get();
        }

        $totalMaquinas = $maquinaria->count();
        $operativas = $maquinaria->where('estado', 'operativa')->count();
        $enMantenimiento = $maquinaria->where('estado', 'mantenimiento')->count();
        $eficiencia = $totalMaquinas > 0 ? ($operativas / $totalMaquinas) * 100 : 0;

        $estadisticas = [
            'total' => $totalMaquinas,
            'operativas' => $operativas,
            'mantenimiento' => $enMantenimiento,
            'fuera_servicio' => $maquinaria->where('estado', 'fuera_servicio')->count(),
            'eficiencia' => round($eficiencia, 2),
            'costo_total_mantenimiento' => $maquinaria->sum(fn($m) => $m->costoMantenimientoTotal()),
        ];

        return view('maquinaria.index', compact('maquinaria', 'estadisticas'));
    }

    public function create()
    {
        $user = auth()->user();
        $fincas = $user->rol === 'administrador' 
            ? Finca::all() 
            : $user->fincas;

        return view('maquinaria.create', compact('fincas'));
    }

    public function store(MaquinariaRequest $request)
    {
        $validated = $request->validated();
        
        // Additional security: verify finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        Maquinaria::create($validated);

        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria registrada exitosamente');
    }

    public function edit(Maquinaria $maquinaria)
    {
        $this->authorize('update', $maquinaria);
        
        $user = auth()->user();
        $fincas = $user->rol === 'administrador' 
            ? Finca::all() 
            : $user->fincas;

        return view('maquinaria.edit', compact('maquinaria', 'fincas'));
    }

    public function update(MaquinariaRequest $request, Maquinaria $maquinaria)
    {
        $this->authorize('update', $maquinaria);

        $validated = $request->validated();
        
        // Additional security: verify finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $maquinaria->update($validated);

        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria actualizada exitosamente');
    }

    public function destroy(Maquinaria $maquinaria)
    {
        $this->authorize('delete', $maquinaria);
        
        $maquinaria->delete();

        return redirect()->route('maquinaria.index')->with('success', 'Maquinaria eliminada exitosamente');
    }
}
