<?php

namespace App\Services;

use App\Models\Cultivo;
use App\Models\Ganado;
use App\Models\Maquinaria;

class IGACalculator
{
    /**
     * Calcula el Índice Global AgroTrack (IGA)
     * Combina IDC promedio, ISA y Eficiencia de Maquinaria
     */
    public static function calcular($userId = null, $fincaId = null)
    {
        // 1. Calcular IDC promedio de cultivos
        $cultivos = Cultivo::when($userId, function($query) use ($userId) {
            return $query->whereHas('finca', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        })->when($fincaId, function($query) use ($fincaId) {
            return $query->where('finca_id', $fincaId);
        })->get();

        $idcPromedio = $cultivos->avg('idc') ?? 0;

        // 2. Calcular ISA (Índice de Sanidad Animal)
        $ganadoQuery = Ganado::query();
        
        if ($fincaId) {
            $ganadoQuery->where('finca_id', $fincaId);
        } elseif ($userId) {
            $ganadoQuery->whereHas('finca', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        $ganado = $ganadoQuery->get();
        $totalAnimales = $ganado->count();
        $animalesVacunados = $ganado->filter(fn($g) => $g->estaVacunado())->count();
        $isa = $totalAnimales > 0 ? ($animalesVacunados / $totalAnimales) * 100 : 0;

        // 3. Calcular Eficiencia de Maquinaria
        $maquinariaQuery = Maquinaria::query();
        
        if ($fincaId) {
            $maquinariaQuery->where('finca_id', $fincaId);
        } elseif ($userId) {
            $maquinariaQuery->whereHas('finca', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        }

        $maquinaria = $maquinariaQuery->get();
        $totalMaquinas = $maquinaria->count();
        $operativas = $maquinaria->where('estado', 'operativa')->count();
        $eficienciaMaquinaria = $totalMaquinas > 0 ? ($operativas / $totalMaquinas) * 100 : 0;

        // 4. Calcular IGA (promedio ponderado)
        // Pesos: IDC 40%, ISA 30%, Eficiencia Maquinaria 30%
        $iga = ($idcPromedio * 0.4) + ($isa * 0.3) + ($eficienciaMaquinaria * 0.3);

        return [
            'iga' => round($iga, 2),
            'idc_promedio' => round($idcPromedio, 2),
            'isa' => round($isa, 2),
            'eficiencia_maquinaria' => round($eficienciaMaquinaria, 2),
            'nivel' => self::determinarNivel($iga),
            'color' => self::determinarColor($iga),
        ];
    }

    private static function determinarNivel($iga)
    {
        if ($iga >= 90) return 'EXCELENTE';
        if ($iga >= 75) return 'BUENO';
        if ($iga >= 60) return 'REGULAR';
        return 'NECESITA MEJORA';
    }

    private static function determinarColor($iga)
    {
        if ($iga >= 90) return '#2E7D32'; // Verde intenso
        if ($iga >= 75) return '#81C784'; // Verde medio
        if ($iga >= 60) return '#FBC02D'; // Amarillo
        return '#E57373'; // Rojo claro
    }
}
