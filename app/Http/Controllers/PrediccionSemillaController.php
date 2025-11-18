<?php

namespace App\Http\Controllers;

use App\Models\PrediccionSemilla;
use App\Models\Cultivo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PrediccionSemillaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', PrediccionSemilla::class);

        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $predicciones = PrediccionSemilla::with('cultivo')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $predicciones = PrediccionSemilla::with('cultivo')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $cultivos = Cultivo::all();

        return view('predicciones.index', compact('predicciones', 'cultivos'));
    }

    public function calcular(Request $request)
    {
        \Log::info('Iniciando cálculo de predicción', ['request' => $request->all()]);
        
        try {
            $validated = $request->validate([
                'cultivo_id' => 'required|exists:cultivos,id',
                'area_hectareas' => 'required|numeric|min:0.01',
                'temperatura_promedio' => 'nullable|numeric',
                'humedad_promedio' => 'nullable|numeric|min:0|max:100',
                'ph_suelo' => 'nullable|numeric|min:0|max:14',
            ]);

        $cultivo = Cultivo::findOrFail($validated['cultivo_id']);
        
        // Obtener datos históricos del usuario para este cultivo
        $historicos = PrediccionSemilla::where('user_id', auth()->id())
            ->where('cultivo_id', $cultivo->id)
            ->get();

        // Calcular valores promedio históricos
        $usoPromedio = $historicos->isNotEmpty() 
            ? $historicos->avg('uso_promedio_historico') 
            : 50; // kg/ha por defecto
            
        $desperdicioPromedio = $historicos->isNotEmpty() 
            ? $historicos->avg('factor_desperdicio') 
            : 0.10; // 10% desperdicio por defecto

        // Calcular factor climático basado en temperatura y humedad
        $factorClimatico = 1.0;
        $temperatura = $request->temperatura_promedio ?? 25;
        $humedad = $request->humedad_promedio ?? 70;
        
        // Ajuste por temperatura
        if ($temperatura < 15) {
            $factorClimatico *= 0.85; // Clima frío reduce eficiencia
        } elseif ($temperatura > 35) {
            $factorClimatico *= 0.80; // Clima muy caliente reduce eficiencia
        }

        // Ajuste por humedad
        if ($humedad < 50) {
            $factorClimatico *= 0.90; // Baja humedad reduce eficiencia
        } elseif ($humedad > 85) {
            $factorClimatico *= 0.88; // Alta humedad puede causar problemas
        }

        // Densidad de siembra típica por hectárea
        $densidadSiembra = 1.0;

        // Calcular predicción
        $paquetesPredichos = PrediccionSemilla::calcularPrediccion(
            floatval($validated['area_hectareas']),
            $densidadSiembra,
            $usoPromedio,
            $factorClimatico,
            $desperdicioPromedio
        );

        // Calcular ahorro estimado vs. compra histórica
        $compraPromedio = $historicos->isNotEmpty() 
            ? $historicos->avg('paquetes_predichos') 
            : null;
            
        $ahorroEstimado = ($compraPromedio && $compraPromedio > 0) 
            ? (($compraPromedio - $paquetesPredichos) / $compraPromedio) * 100 
            : 0;

        // Determinar nivel de confianza
        $nivelConfianza = PrediccionSemilla::determinarNivelConfianza(
            $temperatura,
            $humedad,
            $factorClimatico
        );

        \Log::info('Cálculos completados', [
            'usoPromedio' => $usoPromedio,
            'desperdicioPromedio' => $desperdicioPromedio,
            'factorClimatico' => $factorClimatico,
            'paquetesPredichos' => $paquetesPredichos,
            'ahorroEstimado' => $ahorroEstimado,
            'nivelConfianza' => $nivelConfianza
        ]);

        // Guardar predicción
        $prediccion = PrediccionSemilla::create([
            'user_id' => auth()->id(),
            'cultivo_id' => $cultivo->id,
            'area_hectareas' => $validated['area_hectareas'],
            'temperatura_promedio' => $temperatura,
            'humedad_promedio' => $humedad,
            'ph_suelo' => $request->ph_suelo,
            'densidad_siembra' => $densidadSiembra,
            'uso_promedio_historico' => $usoPromedio,
            'factor_desperdicio' => $desperdicioPromedio,
            'factor_climatico' => $factorClimatico,
            'paquetes_predichos' => $paquetesPredichos,
            'ahorro_estimado_porcentaje' => round($ahorroEstimado, 2),
            'nivel_confianza' => $nivelConfianza,
        ]);

        \Log::info('Predicción guardada exitosamente', ['prediccion_id' => $prediccion->id]);

        return response()->json([
            'success' => true,
            'prediccion' => $prediccion,
            'mensaje' => "Necesitarás aproximadamente " . number_format($paquetesPredichos, 2) . " kg de semillas.",
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Error de validación: ' . $e->getMessage(), ['errors' => $e->errors()]);
        
        return response()->json([
            'success' => false,
            'mensaje' => 'Error de validación',
            'errors' => $e->errors(),
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Error al calcular predicción: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());
        \Log::error('Línea: ' . $e->getLine());
        \Log::error('Archivo: ' . $e->getFile());
        
        return response()->json([
            'success' => false,
            'mensaje' => 'Error al calcular la predicción: ' . $e->getMessage(),
            'debug' => config('app.debug') ? [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ] : null,
        ], 500);
    }
    }

    public function show(PrediccionSemilla $prediccion)
    {
        $this->authorize('view', $prediccion);
        return view('predicciones.show', compact('prediccion'));
    }
}