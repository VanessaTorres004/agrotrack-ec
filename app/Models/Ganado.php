<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganado extends Model
{
    use HasFactory;

    protected $table = 'ganado';

    protected $fillable = [
        'finca_id',
        'identificador',
        'tipo',
        'raza',
        'edad_meses',
        'peso_kg',
        'estado_salud',
        'observaciones',
        'fecha_ingreso',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'peso_kg' => 'decimal:2',
    ];

    public function finca()
    {
        return $this->belongsTo(Finca::class);
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class);
    }

    public function estaVacunado()
    {
        return $this->vacunas()
            ->where('estado', 'aplicada')
            ->whereDate('fecha_aplicacion', '>=', now()->subYear())
            ->exists();
    }

    public function tieneVacunaPendiente()
    {
        return $this->vacunas()
            ->where('estado', 'proxima')
            ->whereDate('proxima_dosis', '<=', now()->addDays(7))
            ->exists();
    }

    public function tieneVacunaVencida()
    {
        return $this->vacunas()
            ->where('estado', 'vencida')
            ->exists();
    }
}
