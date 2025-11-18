<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'cedula',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fincas()
    {
        return $this->hasMany(Finca::class);
    }

    // Add this method to access cultivos through fincas
    public function cultivos()
    {
        return $this->hasManyThrough(Cultivo::class, Finca::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProductor()
    {
        return $this->role === 'productor';
    }
};