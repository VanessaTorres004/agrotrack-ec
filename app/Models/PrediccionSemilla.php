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
     * Fórmula: Predicción = Área × Densidad × UsoPromedio × FactorClima × (1 - Desperdicio)
     */
    public static function calcularPrediccion($area, $densidad, $usoPromedio, $factorClima, $desperdicio)
    {
        return $area * $densidad * $usoPromedio * $factorClima * (1 - $desperdicio);
    }

    /**
     * Determina el nivel de confianza basado en factores climáticos
     */
    public static function determinarNivelConfianza($temperatura, $humedad, $factorClimatico)
    {
        // Temperatura óptima: 20-30°C
        // Humedad óptima: 60-80%
        
        $riesgoTemperatura = ($temperatura < 15 || $temperatura > 35);
        $riesgoHumedad = ($humedad < 50 || $humedad > 85);
        $riesgoClimatico = ($factorClimatico < 0.85);

        if ($riesgoTemperatura || $riesgoHumedad || $riesgoClimatico) {
            return 'riesgo';
        } elseif ($factorClimatico < 0.95) {
            return 'variable';
        }

        return 'estable';
    }
}
