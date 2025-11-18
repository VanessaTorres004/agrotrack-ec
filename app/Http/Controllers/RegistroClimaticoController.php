<?php

namespace App\Http\Controllers;

use App\Models\RegistroClimatico;
use App\Models\Finca;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistroClimaticoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', RegistroClimatico::class);
        
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $registros = RegistroClimatico::with('finca')
                ->latest('fecha')
                ->paginate(30);
        } else {
            $registros = RegistroClimatico::whereHas('finca', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('finca')
            ->latest('fecha')
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
            'finca_id' => 'required|exists:fincas,id',
            'fecha' => 'required|date',
            'temperatura_min' => 'required|numeric',
            'temperatura_max' => 'required|numeric',
            'precipitacion_mm' => 'required|numeric|min:0',
            'humedad_relativa' => 'required|numeric|min:0|max:100',
        ]);

        RegistroClimatico::create($validated);

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático creado exitosamente');
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
            'finca_id' => 'required|exists:fincas,id',
            'fecha' => 'required|date',
            'temperatura_min' => 'required|numeric',
            'temperatura_max' => 'required|numeric',
            'precipitacion_mm' => 'required|numeric|min:0',
            'humedad_relativa' => 'required|numeric|min:0|max:100',
        ]);

        $clima->update($validated);

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático actualizado exitosamente');
    }

    public function destroy(RegistroClimatico $clima)
    {
        $this->authorize('delete', $clima);
        $clima->delete();

        return redirect()->route('clima.index')
            ->with('success', 'Registro climático eliminado exitosamente');
    }
}