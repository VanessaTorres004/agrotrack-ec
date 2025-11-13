<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cultivo_id',
        'fecha',
        'comprador',
        'cantidad',
        'unidad',
        'precio_unitario',
        'total',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class);
    }
}
