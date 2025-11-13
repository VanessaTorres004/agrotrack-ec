<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'ganado_id',
        'tipo_vacuna',
        'fecha_aplicacion',
        'proxima_dosis',
        'veterinario',
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'proxima_dosis' => 'date',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vacuna) {
            if ($vacuna->proxima_dosis) {
                $diasRestantes = now()->diffInDays($vacuna->proxima_dosis, false);
                
                if ($diasRestantes < 0) {
                    $vacuna->estado = 'vencida';
                } elseif ($diasRestantes <= 7) {
                    $vacuna->estado = 'proxima';
                } else {
                    $vacuna->estado = 'aplicada';
                }
            }
        });
    }
}
