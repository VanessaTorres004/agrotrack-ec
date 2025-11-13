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
        'fecha',
        'tipo',
        'observaciones',
        'accion_tomada',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
}
