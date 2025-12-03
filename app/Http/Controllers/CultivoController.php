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
            })
            ->with(['finca', 'indicadores' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        // Agregar el indicador como propiedad para las vistas
        $cultivos->each(function($cultivo) {
            $cultivo->indicador = $cultivo->indicadores->first();
        });

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

        // Validar que la finca pertenezca al usuario
        Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Crear cultivo
        $cultivo = Cultivo::create([
            'finca_id'             => $validated['finca_id'],
            'nombre'               => $validated['nombre'],
            'variedad'             => $validated['variedad'] ?? null,
            'hectareas'            => $validated['hectareas'],
            'fecha_siembra'        => $validated['fecha_siembra'],
            'fecha_cosecha_estimada' => $validated['fecha_cosecha_estimada'] ?? null,
            'objetivo_produccion'  => $validated['objetivo_produccion'] ?? null,
            'estado'               => 'activo',
            'notas'                => $validated['notas'] ?? null,
        ]);

        // Calcular IDC inicial (0 si aún no hay cosechas)
        Indicador::calcularIDC($cultivo->id);

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo registrado correctamente');
    }

    public function show(Cultivo $cultivo)
    {
        $this->authorize('view', $cultivo);

        $cultivo->load([
            'actualizaciones' => function($query) {
                $query->latest()->limit(10);
            },
            'cosechas' => function($query) {
                $query->latest()->limit(10);
            },
            'indicadores' => function($query) {
                $query->latest()->limit(1);
            },
            'alertas' => function($query) {
                $query->where('leida', false)->latest();
            },
            'finca.registrosClimaticos' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Agregar indicador como propiedad
        $cultivo->indicador = $cultivo->indicadores->first();

        // DEBUG: Ver qué datos se están cargando
        \Log::info('Cultivo Show Debug:', [
            'cultivo_id' => $cultivo->id,
            'nombre' => $cultivo->nombre,
            'tiene_indicador' => $cultivo->indicador ? 'SI' : 'NO',
            'idc' => $cultivo->indicador?->idc,
            'actualizaciones_count' => $cultivo->actualizaciones->count(),
            'cosechas_count' => $cultivo->cosechas->count(),
        ]);

        return view('cultivos.show', compact('cultivo'));
    }

    public function edit(Cultivo $cultivo)
    {
        $this->authorize('update', $cultivo);

        $fincas = Finca::where('user_id', auth()->id())->get();

        return view('cultivos.edit', compact('cultivo', 'fincas'));
    }

    public function update(Request $request, Cultivo $cultivo)
    {
        $this->authorize('update', $cultivo);

        $validated = $request->validate([
            'finca_id'                => 'required|exists:fincas,id',
            'nombre'                  => 'required|string|max:255',
            'variedad'                => 'nullable|string|max:255',
            'hectareas'               => 'required|numeric|min:0.01',
            'fecha_siembra'           => 'required|date',
            'fecha_cosecha_estimada'  => 'nullable|date|after:fecha_siembra',
            'fecha_cosecha_real'      => 'nullable|date|after:fecha_siembra',
            'objetivo_produccion'     => 'nullable|numeric|min:0',
            'estado'                  => 'required|in:activo,cosechado,inactivo',
            'notas'                   => 'nullable|string|max:1000',
        ]);

        // Validar que NO cambie a una finca que no es suya
        Finca::where('id', $validated['finca_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Actualizar cultivo
        $cultivo->update($validated);

        // Recalcular IDC porque cambió algún parámetro clave
        Indicador::calcularIDC($cultivo->id);

        // Regenerar alertas inteligentes
        Alerta::generarAlertas($cultivo->id);

        return redirect()->route('cultivos.show', $cultivo)
            ->with('success', 'Cultivo actualizado correctamente');
    }

    public function destroy(Cultivo $cultivo)
    {
        $this->authorize('delete', $cultivo);

        $cultivo->delete();

        return redirect()->route('cultivos.index')
            ->with('success', 'Cultivo eliminado exitosamente');
    }

    // Botón manual para recalcular
    public function recalcularIDC(Cultivo $cultivo)
    {
        $this->authorize('view', $cultivo);

        $indicador = Indicador::calcularIDC($cultivo->id);

        Alerta::generarAlertas($cultivo->id);

        return back()->with('success', 'IDC actualizado: ' . number_format($indicador->idc, 2));
    }
}