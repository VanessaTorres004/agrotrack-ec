<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultivo_id',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }

    public static function generarAlertas($cultivo_id)
    {
        $cultivo = Cultivo::find($cultivo_id);
        $indicador = $cultivo->indicadores()->latest()->first();
        
        if (!$indicador) return;
        
        // Alerta por bajo rendimiento
        if ($indicador->idc < 60) {
            self::create([
                'cultivo_id' => $cultivo_id,
                'tipo' => 'bajo_rendimiento',
                'titulo' => 'IDC Crítico',
                'mensaje' => "El cultivo {$cultivo->nombre} tiene un IDC de {$indicador->idc}. Se requiere atención inmediata.",
            ]);
        }
        
        // Alerta por clima adverso
        if ($indicador->factor_clima < 0.98) {
            self::create([
                'cultivo_id' => $cultivo_id,
                'tipo' => 'clima_adverso',
                'titulo' => 'Condiciones Climáticas Adversas',
                'mensaje' => "Las condiciones climáticas están afectando el cultivo {$cultivo->nombre}.",
            ]);
        }
        
        // Alerta por registros desactualizados
        $ultimaActualizacion = $cultivo->actualizaciones()->latest()->first();
        if (!$ultimaActualizacion || $ultimaActualizacion->fecha->diffInDays(now()) > 14) {
            self::create([
                'cultivo_id' => $cultivo_id,
                'tipo' => 'registro_desactualizado',
                'titulo' => 'Registros Desactualizados',
                'mensaje' => "El cultivo {$cultivo->nombre} no tiene actualizaciones recientes.",
            ]);
        }
    }
}
