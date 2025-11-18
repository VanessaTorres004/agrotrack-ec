<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrediccionSemilla extends Model
{
    use HasFactory;

    protected $table = 'predicciones_semillas';

    protected $fillable = [
        'user_id',
        'cultivo_id',
        'area_hectareas',
        'temperatura_promedio',
        'humedad_promedio',
        'ph_suelo',
        'densidad_siembra',
        'uso_promedio_historico',
        'factor_desperdicio',
        'factor_climatico',
        'paquetes_predichos',
        'ahorro_estimado_porcentaje',
        'nivel_confianza',
    ];

    protected $casts = [
        'area_hectareas' => 'decimal:2',
        'temperatura_promedio' => 'decimal:2',
        'humedad_promedio' => 'decimal:2',
        'ph_suelo' => 'decimal:2',
        'densidad_siembra' => 'decimal:2',
        'uso_promedio_historico' => 'decimal:2',
        'factor_desperdicio' => 'decimal:4',
        'factor_climatico' => 'decimal:4',
        'paquetes_predichos' => 'decimal:2',
        'ahorro_estimado_porcentaje' => 'decimal:2',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    /**
     * Calcula la predicción de semillas necesarias
     * Fórmula: Predicción = Área × Densidad × UsoPromedio × FactorClima × (1 + Desperdicio)
     * 
     * @param float $area - Área en hectáreas
     * @param float $densidad - Densidad de siembra
     * @param float $usoPromedio - Uso promedio histórico (kg/ha)
     * @param float $factorClima - Factor climático (0.0 - 1.0)
     * @param float $desperdicio - Factor de desperdicio (0.0 - 1.0, ej: 0.10 = 10%)
     * @return float - Cantidad de semillas en kg
     */
    public static function calcularPrediccion($area, $densidad, $usoPromedio, $factorClima, $desperdicio)
    {
        // Cálculo base: área × densidad × uso promedio × factor climático
        $base = $area * $densidad * $usoPromedio * $factorClima;
        
        // Agregar desperdicio (si desperdicio = 0.10, se agrega 10% más de semillas)
        $conDesperdicio = $base * (1 + $desperdicio);
        
        return round($conDesperdicio, 2);
    }

    /**
     * Determina el nivel de confianza basado en factores climáticos
     * 
     * @param float $temperatura - Temperatura promedio en °C
     * @param float $humedad - Humedad relativa promedio en %
     * @param float $factorClimatico - Factor climático calculado (0.0 - 1.0)
     * @return string - 'estable', 'variable', o 'riesgo'
     */
    public static function determinarNivelConfianza($temperatura, $humedad, $factorClimatico)
    {
        // Rangos óptimos:
        // Temperatura óptima: 20-30°C
        // Humedad óptima: 60-80%
        
        $riesgoTemperatura = ($temperatura < 15 || $temperatura > 35);
        $riesgoHumedad = ($humedad < 50 || $humedad > 85);
        $riesgoClimatico = ($factorClimatico < 0.85);

        // Si hay condiciones de riesgo
        if ($riesgoTemperatura || $riesgoHumedad || $riesgoClimatico) {
            return 'riesgo';
        } 
        
        // Si el factor climático está entre 0.85 y 0.95
        if ($factorClimatico < 0.95) {
            return 'variable';
        }

        // Condiciones óptimas
        return 'estable';
    }

    /**
     * Obtiene las predicciones del usuario con filtros opcionales
     * 
     * @param int $userId - ID del usuario
     * @param int|null $cultivoId - ID del cultivo (opcional)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function obtenerPrediccionesUsuario($userId, $cultivoId = null)
    {
        $query = self::where('user_id', $userId)
            ->with('cultivo')
            ->orderBy('created_at', 'desc');

        if ($cultivoId) {
            $query->where('cultivo_id', $cultivoId);
        }

        return $query->get();
    }

    /**
     * Calcula estadísticas agregadas de predicciones del usuario
     * 
     * @param int $userId - ID del usuario
     * @return array - Array con estadísticas
     */
    public static function estadisticas($userId)
    {
        $predicciones = self::where('user_id', $userId)->get();

        if ($predicciones->isEmpty()) {
            return [
                'total_predicciones' => 0,
                'ahorro_promedio' => 0,
                'area_total' => 0,
                'semillas_totales' => 0,
            ];
        }

        return [
            'total_predicciones' => $predicciones->count(),
            'ahorro_promedio' => round($predicciones->avg('ahorro_estimado_porcentaje'), 2),
            'area_total' => round($predicciones->sum('area_hectareas'), 2),
            'semillas_totales' => round($predicciones->sum('paquetes_predichos'), 2),
        ];
    }

    /**
     * Scope para filtrar por nivel de confianza
     */
    public function scopePorNivelConfianza($query, $nivel)
    {
        return $query->where('nivel_confianza', $nivel);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    /**
     * Accessor para obtener el nivel de confianza con emoji
     */
    public function getNivelConfianzaConEmojiAttribute()
    {
        return match($this->nivel_confianza) {
            'estable' => '🟢 Estable',
            'variable' => '🟡 Variable',
            'riesgo' => '🔴 Riesgo',
            default => $this->nivel_confianza,
        };
    }

    /**
     * Accessor para obtener el texto de ahorro formateado
     */
    public function getAhorroFormateadoAttribute()
    {
        if (!$this->ahorro_estimado_porcentaje) {
            return 'N/A';
        }

        return number_format($this->ahorro_estimado_porcentaje, 1) . '%';
    }
}