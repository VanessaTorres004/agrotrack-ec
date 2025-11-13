<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;

    protected $table = 'maquinaria';

    protected $fillable = [
        'finca_id',
        'identificador',
        'tipo',
        'marca',
        'modelo',
        'horas_uso',
        'estado',
        'fecha_ultimo_servicio',
        'fecha_proximo_servicio',
    ];

    protected $casts = [
        'fecha_ultimo_servicio' => 'date',
        'fecha_proximo_servicio' => 'date',
    ];

    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }

    public function necesitaMantenimiento()
    {
        if (!$this->fecha_proximo_servicio) {
            return false;
        }

        $diasRestantes = now()->diffInDays($this->fecha_proximo_servicio, false);
        return $diasRestantes <= 3 && $diasRestantes >= 0;
    }

    public function mantenimientoVencido()
    {
        if (!$this->fecha_proximo_servicio) {
            return false;
        }

        return now()->gt($this->fecha_proximo_servicio);
    }

    public function costoMantenimientoTotal()
    {
        return $this->mantenimientos()->sum('costo');
    }
}
