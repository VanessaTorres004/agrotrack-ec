<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'maquinaria_id',
        'fecha_mantenimiento',
        'tipo',
        'descripcion',
        'costo',
        'tecnico',
    ];

    protected $casts = [
        'fecha_mantenimiento' => 'date',
        'costo' => 'decimal:2',
    ];

    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class);
    }
}
