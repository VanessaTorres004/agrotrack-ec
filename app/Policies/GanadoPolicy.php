<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ganado;

class GanadoPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the ganado list
    }

    public function view(User $user, Ganado $ganado): bool
    {
        return $user->isAdmin() || $ganado->finca->user_id === $user->id;
    }

    public function update(User $user, Ganado $ganado): bool
    {
        return $user->isAdmin() || $ganado->finca->user_id === $user->id;
    }

    public function delete(User $user, Ganado $ganado): bool
    {
        return $user->isAdmin() || $ganado->finca->user_id === $user->id;
    }
}
