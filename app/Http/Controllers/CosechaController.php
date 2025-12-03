<?php

namespace App\Http\Controllers;

use App\Models\Cosecha;
use App\Models\Cultivo;
use App\Models\Indicador;
use Illuminate\Http\Request;

class CosechaController extends Controller
{
    public function index()
    {
        $cosechas = Cosecha::whereHas('cultivo.finca', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('cultivo.finca')
            ->orderByDesc('fecha_cosecha')
            ->paginate(20);

        return view('cosechas.index', compact('cosechas'));
    }

    public function create()
    {
        $cultivos = Cultivo::whereHas('finca', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('estado', 'activo')
            ->with('finca')
            ->get();

        return view('cosechas.create', compact('cultivos'));
    }

    public function store(Request $request)
    {
        // DEBUG: Ver qué datos llegan
        \Log::info('Datos del request:', $request->all());

        $validated = $request->validate([
            'cultivo_id'    => 'required|exists:cultivos,id',
            'fecha_cosecha' => 'required|date',
            'cantidad_kg'   => 'required|numeric|min:0',
            'calidad'       => 'required|in:excelente,buena,regular,mala',
            'mermas'        => 'required|numeric|min:0|max:100',
            'precio_kg'     => 'required|numeric|min:0',
            'notas'         => 'nullable|string|max:1000',
        ]);

        // DEBUG: Ver qué datos se validaron
        \Log::info('Datos validados:', $validated);

        // Validar que el cultivo pertenezca al usuario
        $cultivo = Cultivo::where('id', $validated['cultivo_id'])
            ->whereHas('finca', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        // Registrar cosecha
        $cosecha = Cosecha::create($validated);

        // Actualizar fecha real de cosecha en el cultivo
        if (!$cultivo->fecha_cosecha_real || $validated['fecha_cosecha'] > $cultivo->fecha_cosecha_real) {
            $cultivo->update([
                'fecha_cosecha_real' => $validated['fecha_cosecha']
            ]);
        }

        // Recalcular IDC del cultivo
        Indicador::calcularIDC($cultivo->id);

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha registrada correctamente');
    }

    public function edit(Cosecha $cosecha)
    {
        $this->authorize('update', $cosecha);

        $cultivos = Cultivo::whereHas('finca', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('finca')
            ->get();

        return view('cosechas.edit', compact('cosecha', 'cultivos'));
    }

    public function update(Request $request, Cosecha $cosecha)
    {
        $this->authorize('update', $cosecha);

        $validated = $request->validate([
            'cultivo_id'    => 'required|exists:cultivos,id',
            'fecha_cosecha' => 'required|date',
            'cantidad_kg'   => 'required|numeric|min:0',
            'calidad'       => 'required|in:excelente,buena,regular,mala',
            'mermas'        => 'required|numeric|min:0|max:100',
            'precio_kg'     => 'required|numeric|min:0',
            'notas'         => 'nullable|string|max:1000',
        ]);

        $cosecha->update($validated);

        // Actualizar fecha real de cosecha en el cultivo
        $cultivo = $cosecha->cultivo;

        if (!$cultivo->fecha_cosecha_real || $validated['fecha_cosecha'] > $cultivo->fecha_cosecha_real) {
            $cultivo->update([
                'fecha_cosecha_real' => $validated['fecha_cosecha']
            ]);
        }

        // Recalcular IDC
        Indicador::calcularIDC($cultivo->id);

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha actualizada correctamente');
    }

    public function destroy(Cosecha $cosecha)
    {
        $this->authorize('delete', $cosecha);

        $cultivo = $cosecha->cultivo;
        $cosecha->delete();

        // Recalcular IDC después de eliminar
        Indicador::calcularIDC($cultivo->id);

        return redirect()->route('cosechas.index')
            ->with('success', 'Cosecha eliminada');
    }
}