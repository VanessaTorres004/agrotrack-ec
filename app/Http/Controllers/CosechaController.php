<?php

namespace App\Http\Controllers;

use App\Models\Cosecha;
use App\Models\Cultivo;
use Illuminate\Http\Request;

class CosechaController extends Controller
{
    public function index()
    {
        $cosechas = Cosecha::whereHas('cultivo.finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('cultivo.finca')
        ->latest('fecha_cosecha')
        ->paginate(20);

        return view('cosechas.index', compact('cosechas'));
    }

    public function create()
    {
        $cultivos = Cultivo::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('estado', 'activo')
        ->get();

        return view('cosechas.create', compact('cultivos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cultivo_id' => 'required|exists:cultivos,id',
            'fecha_cosecha' => 'required|date',
            'cantidad_kg' => 'required|numeric|min:0',
            'calidad' => 'required|in:excelente,buena,regular,mala',
            'observaciones' => 'nullable|string',
        ]);

        Cosecha::create($validated);

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha registrada exitosamente');
    }

    public function show(Cosecha $cosecha)
    {
        $this->authorize('view', $cosecha);
        return view('cosechas.show', compact('cosecha'));
    }

    public function edit(Cosecha $cosecha)
    {
        $this->authorize('update', $cosecha);
        
        $cultivos = Cultivo::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return view('cosechas.edit', compact('cosecha', 'cultivos'));
    }

    public function update(Request $request, Cosecha $cosecha)
    {
        $this->authorize('update', $cosecha);

        $validated = $request->validate([
            'cultivo_id' => 'required|exists:cultivos,id',
            'fecha_cosecha' => 'required|date',
            'cantidad_kg' => 'required|numeric|min:0',
            'calidad' => 'required|in:excelente,buena,regular,mala',
            'observaciones' => 'nullable|string',
        ]);

        $cosecha->update($validated);

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha actualizada exitosamente');
    }

    public function destroy(Cosecha $cosecha)
    {
        $this->authorize('delete', $cosecha);
        $cosecha->delete();

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha eliminada exitosamente');
    }
}
