<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ganado;

class GanadoPolicy
{
    public function view(User $user, Ganado $ganado): bool
    {
        return $user->rol === 'administrador' || $ganado->finca->user_id === $user->id;
    }

    public function update(User $user, Ganado $ganado): bool
    {
        return $user->rol === 'administrador' || $ganado->finca->user_id === $user->id;
    }

    public function delete(User $user, Ganado $ganado): bool
    {
        return $user->rol === 'administrador' || $ganado->finca->user_id === $user->id;
    }
}
