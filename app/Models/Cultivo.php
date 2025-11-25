<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'finca_id',
        'nombre',
        'variedad',
        'hectareas',
        'fecha_siembra',
        'fecha_cosecha_estimada',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_siembra' => 'date',
        'fecha_cosecha_estimada' => 'date',
    ];

    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function actualizaciones()
    {
        return $this->hasMany(Actualizacion::class);
    }

    public function cosechas()
    {
        return $this->hasMany(Cosecha::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function indicadores()
    {
        return $this->hasMany(Indicador::class);
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    public function getIdcActualAttribute()
    {
        return $this->indicadores()->latest()->first()?->idc ?? 0;
    }

    public function getClasificacionActualAttribute()
    {
        return $this->indicadores()->latest()->first()?->clasificacion ?? 'sin_datos';
    }
}
