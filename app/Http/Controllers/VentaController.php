<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cosecha;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::whereHas('cosecha.cultivo.finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('cosecha.cultivo')
        ->latest('fecha')
        ->paginate(20);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $cosechas = Cosecha::whereHas('cultivo.finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('cultivo')
        ->get();

        return view('ventas.create', compact('cosechas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cosecha_id' => 'required|exists:cosechas,id',
            'fecha' => 'required|date',
            'cantidad_vendida_kg' => 'required|numeric|min:0',
            'precio_kg' => 'required|numeric|min:0',
            'cliente' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $validated['total'] = $validated['cantidad_vendida_kg'] * $validated['precio_kg'];

        Venta::create($validated);

        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada exitosamente');
    }

    public function show(Venta $venta)
    {
        $this->authorize('view', $venta);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $this->authorize('update', $venta);
        
        $cosechas = Cosecha::whereHas('cultivo.finca', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('cultivo')
        ->get();

        return view('ventas.edit', compact('venta', 'cosechas'));
    }

    public function update(Request $request, Venta $venta)
    {
        $this->authorize('update', $venta);

        $validated = $request->validate([
            'cosecha_id' => 'required|exists:cosechas,id',
            'fecha' => 'required|date',
            'cantidad_vendida_kg' => 'required|numeric|min:0',
            'precio_kg' => 'required|numeric|min:0',
            'cliente' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $validated['total'] = $validated['cantidad_vendida_kg'] * $validated['precio_kg'];

        $venta->update($validated);

        return redirect()->route('ventas.index')
            ->with('success', 'Venta actualizada exitosamente');
    }

    public function destroy(Venta $venta)
    {
        $this->authorize('delete', $venta);
        $venta->delete();

        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada exitosamente');
    }
}
