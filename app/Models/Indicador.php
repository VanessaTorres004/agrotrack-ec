<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    use HasFactory;

    protected $table = 'indicadores';

    protected $fillable = [
        'cultivo_id',
        'fecha_calculo',
        'rendimiento',
        'oportunidad',
        'calidad',
        'registro',
        'factor_clima',
        'idc',
        'clasificacion',
    ];

    protected $casts = [
        'fecha_calculo' => 'date',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    public static function calcularIDC($cultivo_id)
    {
        $cultivo = Cultivo::find($cultivo_id);
        
        // Calcular rendimiento basado en cosechas
        $rendimiento = self::calcularRendimiento($cultivo);
        
        // Calcular oportunidad basado en fechas
        $oportunidad = self::calcularOportunidad($cultivo);
        
        // Calcular calidad basado en cosechas
        $calidad = self::calcularCalidad($cultivo);
        
        // Calcular registro basado en actualizaciones
        $registro = self::calcularRegistro($cultivo);
        
        // Obtener factor climático
        $factorClima = self::obtenerFactorClima($cultivo);
        
        // Aplicar fórmula IDC
        $idc = (0.50 * $rendimiento + 0.20 * $oportunidad + 0.20 * $calidad + 0.10 * $registro) * $factorClima;
        
        // Clasificar
        $clasificacion = self::clasificarIDC($idc);
        
        // Guardar indicador
        return Indicador::create([
            'cultivo_id' => $cultivo_id,
            'fecha_calculo' => now(),
            'rendimiento' => $rendimiento,
            'oportunidad' => $oportunidad,
            'calidad' => $calidad,
            'registro' => $registro,
            'factor_clima' => $factorClima,
            'idc' => $idc,
            'clasificacion' => $clasificacion,
        ]);
    }

    private static function calcularRendimiento($cultivo)
    {
        $cosechas = $cultivo->cosechas;
        if ($cosechas->isEmpty()) return 50;
        
        $totalCosecha = $cosechas->sum('cantidad');
        $areaHa = $cultivo->hectareas; // Fixed from 'area' to 'hectareas'
        
        // Rendimiento por hectárea (simplificado)
        $rendimientoHa = $areaHa > 0 ? $totalCosecha / $areaHa : 0;
        
        // Normalizar a 0-100 (ajustar según cultivo)
        return min(100, ($rendimientoHa / 10) * 100);
    }

    private static function calcularOportunidad($cultivo)
    {
        if (!$cultivo->fecha_cosecha_estimada) return 70;
        
        $diasTranscurridos = now()->diffInDays($cultivo->fecha_siembra);
        $diasEstimados = $cultivo->fecha_cosecha_estimada->diffInDays($cultivo->fecha_siembra);
        
        if ($diasEstimados == 0) return 70;
        
        $porcentajeProgreso = ($diasTranscurridos / $diasEstimados) * 100;
        
        // Penalizar si está muy atrasado o adelantado
        if ($porcentajeProgreso > 110) return 60;
        if ($porcentajeProgreso < 80) return 80;
        
        return 85;
    }

    private static function calcularCalidad($cultivo)
    {
        $cosechas = $cultivo->cosechas;
        if ($cosechas->isEmpty()) return 70;
        
        $calidadMap = ['excelente' => 100, 'buena' => 80, 'regular' => 60, 'mala' => 40];
        $promedioCalidad = $cosechas->avg(fn($c) => $calidadMap[$c->calidad] ?? 70);
        
        return $promedioCalidad;
    }

    private static function calcularRegistro($cultivo)
    {
        $actualizaciones = $cultivo->actualizaciones()->where('fecha', '>=', now()->subDays(30))->count();
        
        // Más actualizaciones = mejor registro
        if ($actualizaciones >= 8) return 100;
        if ($actualizaciones >= 4) return 80;
        if ($actualizaciones >= 2) return 60;
        
        return 40;
    }

    private static function obtenerFactorClima($cultivo)
    {
        $finca = $cultivo->finca;
        $registroClima = $finca->registrosClimaticos()->latest()->first();
        
        return $registroClima?->factor_clima ?? 1.00;
    }

    private static function clasificarIDC($idc)
    {
        if ($idc >= 90) return 'excelente';
        if ($idc >= 80) return 'bueno';
        if ($idc >= 60) return 'en_riesgo';
        return 'critico';
    }
}
