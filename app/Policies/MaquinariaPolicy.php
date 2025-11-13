<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Maquinaria;

class MaquinariaPolicy
{
    public function view(User $user, Maquinaria $maquinaria): bool
    {
        return $user->rol === 'administrador' || $maquinaria->finca->user_id === $user->id;
    }

    public function update(User $user, Maquinaria $maquinaria): bool
    {
        return $user->rol === 'administrador' || $maquinaria->finca->user_id === $user->id;
    }

    public function delete(User $user, Maquinaria $maquinaria): bool
    {
        return $user->rol === 'administrador' || $maquinaria->finca->user_id === $user->id;
    }
}
