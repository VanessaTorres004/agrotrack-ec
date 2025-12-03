<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actualizacion extends Model
{
    use HasFactory;

    protected $table = 'actualizaciones';

    protected $fillable = [
        'cultivo_id',
        'fecha_actividad',
        'tipo_actividad',
        'descripcion',
        'accion_tomada',
        'costo',
    ];

    protected $casts = [
        'fecha_actividad' => 'date',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
}
