<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cosecha extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultivo_id',
        'fecha_cosecha',
        'cantidad_kg',
        'precio_kg',        // ← AGREGAR ESTA LÍNEA
        'calidad',
        'unidad',
        'mermas',
        'notas',
    ];

    protected $casts = [
        'fecha_cosecha' => 'date',
        'cantidad_kg' => 'decimal:2',
        'precio_kg' => 'decimal:2',   // ← AGREGAR ESTA LÍNEA
        'mermas' => 'decimal:2',       // ← AGREGAR ESTA LÍNEA
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
}