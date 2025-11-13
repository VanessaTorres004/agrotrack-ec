<?php

namespace App\Http\Controllers;

use App\Models\PrediccionSemilla;
use App\Models\Cultivo;
use Illuminate\Http\Request;

class PrediccionSemillaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $predicciones = PrediccionSemilla::with('cultivo')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $cultivos = Cultivo::all();

        return view('predicciones.index', compact('predicciones', 'cultivos'));
    }

    public function calcular(Request $request)
    {
        $validated = $request->validate([
            'cultivo_id' => 'required|exists:cultivos,id',
            'area_hectareas' => 'required|numeric|min:0.01',
            'temperatura_promedio' => 'nullable|numeric',
            'humedad_promedio' => 'nullable|numeric|min:0|max:100',
            'ph_suelo' => 'nullable|numeric|min:0|max:14',
        ]);

        $cultivo = Cultivo::find($validated['cultivo_id']);
        
        // Obtener datos históricos del usuario para este cultivo
        $historicos = PrediccionSemilla::where('user_id', auth()->id())
            ->where('cultivo_id', $cultivo->id)
            ->get();

        // Calcular valores promedio históricos
        $usoPromedio = $historicos->avg('densidad_siembra') ?? 50; // kg/ha por defecto
        $desperdicioPromedio = $historicos->avg('factor_desperdicio') ?? 0.10;

        // Calcular factor climático basado en temperatura y humedad
        $factorClimatico = 1.0;
        
        if ($request->temperatura_promedio) {
            $temp = $request->temperatura_promedio;
            if ($temp < 15) {
                $factorClimatico *= 0.85; // Clima frío reduce eficiencia
            } elseif ($temp > 35) {
                $factorClimatico *= 0.80; // Clima muy caliente reduce eficiencia
            }
        }

        if ($request->humedad_promedio) {
            $humedad = $request->humedad_promedio;
            if ($humedad < 50) {
                $factorClimatico *= 0.90; // Baja humedad reduce eficiencia
            } elseif ($humedad > 85) {
                $factorClimatico *= 0.88; // Alta humedad puede causar problemas
            }
        }

        // Densidad de siembra típica por hectárea
        $densidadSiembra = 1.0;

        // Calcular predicción
        $paquetesPredichos = PrediccionSemilla::calcularPrediccion(
            $validated['area_hectareas'],
            $densidadSiembra,
            $usoPromedio,
            $factorClimatico,
            $desperdicioPromedio
        );

        // Calcular ahorro estimado vs. compra histórica
        $compraPromedio = $historicos->avg('paquetes_predichos') ?? $paquetesPredichos;
        $ahorroEstimado = $compraPromedio > 0 
            ? (($compraPromedio - $paquetesPredichos) / $compraPromedio) * 100 
            : 0;

        // Determinar nivel de confianza
        $nivelConfianza = PrediccionSemilla::determinarNivelConfianza(
            $request->temperatura_promedio ?? 25,
            $request->humedad_promedio ?? 70,
            $factorClimatico
        );

        // Guardar predicción
        $prediccion = PrediccionSemilla::create([
            'user_id' => auth()->id(),
            'cultivo_id' => $cultivo->id,
            'area_hectareas' => $validated['area_hectareas'],
            'temperatura_promedio' => $request->temperatura_promedio,
            'humedad_promedio' => $request->humedad_promedio,
            'ph_suelo' => $request->ph_suelo,
            'densidad_siembra' => $densidadSiembra,
            'uso_promedio_historico' => $usoPromedio,
            'factor_desperdicio' => $desperdicioPromedio,
            'factor_climatico' => $factorClimatico,
            'paquetes_predichos' => $paquetesPredichos,
            'ahorro_estimado_porcentaje' => $ahorroEstimado,
            'nivel_confianza' => $nivelConfianza,
        ]);

        return response()->json([
            'success' => true,
            'prediccion' => $prediccion,
            'mensaje' => "Necesitarás aproximadamente " . number_format($paquetesPredichos, 2) . " kg de semillas.",
        ]);
    }

    public function show(PrediccionSemilla $prediccion)
    {
        $this->authorize('view', $prediccion);
        return view('predicciones.show', compact('prediccion'));
    }
}

