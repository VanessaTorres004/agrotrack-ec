<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cultivo;

class CultivoPolicy
{
    public function view(User $user, Cultivo $cultivo): bool
    {
        return $user->rol === 'administrador' || $cultivo->finca->user_id === $user->id;
    }

    public function update(User $user, Cultivo $cultivo): bool
    {
        return $user->rol === 'administrador' || $cultivo->finca->user_id === $user->id;
    }

    public function delete(User $user, Cultivo $cultivo): bool
    {
        return $user->rol === 'administrador' || $cultivo->finca->user_id === $user->id;
    }
}
