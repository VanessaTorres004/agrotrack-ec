<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PrediccionSemilla;

class PrediccionSemillaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view predicciones
    }

    public function view(User $user, PrediccionSemilla $prediccion): bool
    {
        return $user->isAdmin() || $prediccion->user_id === $user->id;
    }

    public function update(User $user, PrediccionSemilla $prediccion): bool
    {
        return $user->isAdmin() || $prediccion->user_id === $user->id;
    }

    public function delete(User $user, PrediccionSemilla $prediccion): bool
    {
        return $user->isAdmin() || $prediccion->user_id === $user->id;
    }
}
