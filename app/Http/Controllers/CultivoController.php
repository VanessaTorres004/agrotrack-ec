<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\Finca;
use App\Models\Indicador;
use App\Models\Alerta;
use App\Http\Requests\CultivoRequest;
use Illuminate\Http\Request;

class CultivoController extends Controller
{
    public function index()
    {
        $cultivos = Cultivo::whereHas('finca', function($query) {
            $query->where('user_id', auth()->id());
        })->with('finca', 'indicadores')->get();
        
        return view('cultivos.index', compact('cultivos'));
    }

    public function create()
    {
        $fincas = Finca::where('user_id', auth()->id())->get();
        return view('cultivos.create', compact('fincas'));
    }

    public function store(CultivoRequest $request)
    {
        $validated = $request->validated();
        
        // Additional security check: verify finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $cultivo = Cultivo::create($validated);
        
        // Calcular indicador inicial
        Indicador::calcularIDC($cultivo->id);
        
        return redirect()->route('cultivos.index')->with('success', 'Cultivo creado exitosamente');
    }

    public function show(Cultivo $cultivo)
    {
        $this->authorize('view', $cultivo);
        
        $cultivo->load('actualizaciones', 'cosechas', 'ventas', 'indicadores', 'alertas');
        
        return view('cultivos.show', compact('cultivo'));
    }

    public function edit(Cultivo $cultivo)
    {
        $this->authorize('update', $cultivo);
        
        $fincas = Finca::where('user_id', auth()->id())->get();
        return view('cultivos.edit', compact('cultivo', 'fincas'));
    }

    public function update(CultivoRequest $request, Cultivo $cultivo)
    {
        $this->authorize('update', $cultivo);
        
        $validated = $request->validated();
        
        // Additional security check: verify new finca ownership
        $finca = Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $cultivo->update($validated);
        
        // Recalcular IDC
        Indicador::calcularIDC($cultivo->id);
        Alerta::generarAlertas($cultivo->id);
        
        return redirect()->route('cultivos.show', $cultivo)->with('success', 'Cultivo actualizado exitosamente');
    }

    public function destroy(Cultivo $cultivo)
    {
        $this->authorize('delete', $cultivo);
        
        $cultivo->delete();
        
        return redirect()->route('cultivos.index')->with('success', 'Cultivo eliminado exitosamente');
    }

    public function calcularIndicador(Cultivo $cultivo)
    {
        $this->authorize('view', $cultivo);
        
        $indicador = Indicador::calcularIDC($cultivo->id);
        Alerta::generarAlertas($cultivo->id);
        
        return redirect()->back()->with('success', 'IDC calculado: ' . number_format($indicador->idc, 2));
    }
}
