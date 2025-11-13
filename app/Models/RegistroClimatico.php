<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroClimatico extends Model
{
    use HasFactory;

    protected $fillable = [
        'finca_id',
        'fecha',
        'temperatura_min',
        'temperatura_max',
        'humedad',
        'precipitacion',
        'eventos',
        'factor_clima',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }
}

