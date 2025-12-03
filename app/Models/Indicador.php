<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'fecha_calculo' => 'datetime',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PRINCIPAL: CALCULO IDC
    |--------------------------------------------------------------------------
    */
    public static function calcularIDC($cultivo_id)
    {
        $cultivo = Cultivo::with(['cosechas', 'actualizaciones', 'finca.registrosClimaticos'])->findOrFail($cultivo_id);

        // Subindicadores
        $rendimiento   = self::calcularRendimiento($cultivo);
        $oportunidad   = self::calcularOportunidad($cultivo);
        $calidad       = self::calcularCalidad($cultivo);
        $registro      = self::calcularRegistro($cultivo);
        $factorClima   = self::obtenerFactorClima($cultivo);

        // Fórmula oficial
        $base = (0.50 * $rendimiento)
              + (0.20 * $oportunidad)
              + (0.20 * $calidad)
              + (0.10 * $registro);

        $idc = round($base * $factorClima, 2);

        $clasificacion = self::clasificarIDC($idc);

        return self::create([
            'cultivo_id'      => $cultivo->id,
            'fecha_calculo'   => now(),
            'rendimiento'     => $rendimiento,
            'oportunidad'     => $oportunidad,
            'calidad'         => $calidad,
            'registro'        => $registro,
            'factor_clima'    => $factorClima,
            'idc'             => $idc,
            'clasificacion'   => $clasificacion,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SUBINDICADORES
    |--------------------------------------------------------------------------
    */

    // 1. Rendimiento (50%)
    private static function calcularRendimiento($cultivo)
    {
        if (!$cultivo->objetivo_produccion || $cultivo->objetivo_produccion <= 0) {
            return 50; // Valor por defecto
        }

        $produccionReal = $cultivo->cosechas->sum('cantidad_kg');

        $porcentaje = ($produccionReal / $cultivo->objetivo_produccion) * 100;

        return min(100, max(0, $porcentaje));
    }


    // 2. Oportunidad (20%)
    private static function calcularOportunidad($cultivo)
    {
        if (!$cultivo->fecha_cosecha_estimada) {
            return 70; // No estimado = valor neutro
        }

        if (!$cultivo->fecha_cosecha_real) {
            return 80; // Aun no cosechado
        }

        $difDias = $cultivo->fecha_cosecha_real->diffInDays($cultivo->fecha_cosecha_estimada);

        // Cada día fuera de la ventana óptima (+/- 7 días) resta 2 puntos
        if ($difDias <= 7) return 100;

        $penalizacion = ($difDias - 7) * 2;

        return max(40, 100 - $penalizacion);
    }


    // 3. Calidad (20%)
    private static function calcularCalidad($cultivo)
    {
        if ($cultivo->cosechas->isEmpty()) {
            return 70;
        }

        $map = [
            'excelente' => 1.00,
            'buena'     => 0.95,
            'regular'   => 0.85,
            'mala'      => 0.70,
        ];

        $promedioBase = $cultivo->cosechas->avg(function ($c) {
            return 100 * (1 - (($c->mermas ?? 0) / 100));
        });

        $promedioFactor = $cultivo->cosechas->avg(function ($c) use ($map) {
            return $map[$c->calidad] ?? 0.90;
        });

        return round($promedioBase * $promedioFactor, 2);
    }


    // 4. Registro (10%)
    private static function calcularRegistro($cultivo)
    {
        $totalSemanas = 12; // ventana de 3 meses

        $semanasConActualizaciones = $cultivo->actualizaciones
            ->where('fecha_actividad', '>=', now()->subWeeks($totalSemanas))
            ->groupBy(function ($a) {
                return Carbon::parse($a->fecha_actividad)->format('W');
            })
            ->count();

        return round(($semanasConActualizaciones / $totalSemanas) * 100, 2);
    }


    // Factor clima
    private static function obtenerFactorClima($cultivo)
    {
        $ultimoClima = $cultivo->finca->registrosClimaticos->sortByDesc('fecha')->first();

        if (!$ultimoClima) return 1.00;

        // Si la columna eventos tiene helada, sequía, granizo, etc:
        if ($ultimoClima->eventos) {
            if (stripos($ultimoClima->eventos, 'helada') !== false)   return 0.95;
            if (stripos($ultimoClima->eventos, 'sequía') !== false)  return 0.95;
            if (stripos($ultimoClima->eventos, 'granizo') !== false) return 0.95;
        }

        return $ultimoClima->factor_clima ?? 1.00;
    }


    // Clasificación final
    private static function clasificarIDC($idc)
    {
        return match (true) {
            $idc >= 90 => 'excelente',
            $idc >= 80 => 'bueno',
            $idc >= 60 => 'en_riesgo',
            default    => 'critico',
        };
    }
}
