<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finca extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'ubicacion',
        'area_total',
        'provincia',
        'canton',
        'descripcion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }

    public function registrosClimaticos()
    {
        return $this->hasMany(RegistroClimatico::class);
    }
}
