<?php

namespace App\Http\Controllers;

use App\Models\Ganado;
use App\Models\Finca;
use App\Http\Requests\GanadoRequest;
use Illuminate\Http\Request;

class GanadoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->rol === 'administrador') {
            $ganado = Ganado::with(['finca', 'vacunas'])->get();
        } else {
            $fincas = $user->fincas->pluck('id');
            $ganado = Ganado::with(['finca', 'vacunas'])->whereIn('finca_id', $fincas)->get();
        }

        // Calcular ISA (Índice de Sanidad Animal)
        $totalAnimales = $ganado->count();
        $animalesVacunados = $ganado->filter(fn($g) => $g->estaVacunado())->count();
        $isa = $totalAnimales > 0 ? ($animalesVacunados / $totalAnimales) * 100 : 0;

        $estadisticas = [
            'total' => $totalAnimales,
            'vacunados' => $animalesVacunados,
            'pendientes' => $ganado->filter(fn($g) => $g->tieneVacunaPendiente())->count(),
            'isa' => round($isa, 2),
            'sanos' => $ganado->where('estado_salud', 'sano')->count(),
            'observacion' => $ganado->where('estado_salud', 'observacion')->count(),
            'enfermos' => $ganado->where('estado_salud', 'enfermo')->count(),
        ];

        return view('ganado.index', compact('ganado', 'estadisticas'));
    }

    public function create()
    {
        $user = auth()->user();
        $fincas = $user->rol === 'administrador' 
            ? Finca::all() 
            : $user->fincas;

        return view('ganado.create', compact('fincas'));
    }

    public function store(GanadoRequest $request)
    {
        $validated = $request->validated();
        
        // Additional security: verify finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        Ganado::create($validated);

        return redirect()->route('ganado.index')->with('success', 'Animal registrado exitosamente');
    }

    public function edit(Ganado $ganado)
    {
        $this->authorize('update', $ganado);
        
        $user = auth()->user();
        $fincas = $user->rol === 'administrador' 
            ? Finca::all() 
            : $user->fincas;

        return view('ganado.edit', compact('ganado', 'fincas'));
    }

    public function update(GanadoRequest $request, Ganado $ganado)
    {
        $this->authorize('update', $ganado);

        $validated = $request->validated();
        
        // Additional security: verify finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $ganado->update($validated);

        return redirect()->route('ganado.index')->with('success', 'Animal actualizado exitosamente');
    }

    public function destroy(Ganado $ganado)
    {
        $this->authorize('delete', $ganado);
        
        $ganado->delete();

        return redirect()->route('ganado.index')->with('success', 'Animal eliminado exitosamente');
    }

    public function alertas()
    {
        $user = auth()->user();
        
        if ($user->rol === 'administrador') {
            $ganado = Ganado::with('vacunas')->get();
        } else {
            $fincas = $user->fincas->pluck('id');
            $ganado = Ganado::with('vacunas')->whereIn('finca_id', $fincas)->get();
        }

        $alertas = [
            'proximas' => $ganado->filter(fn($g) => $g->tieneVacunaPendiente()),
            'vencidas' => $ganado->filter(fn($g) => $g->tieneVacunaVencida()),
            'enfermos' => $ganado->where('estado_salud', 'enfermo'),
        ];

        return response()->json($alertas);
    }
}
