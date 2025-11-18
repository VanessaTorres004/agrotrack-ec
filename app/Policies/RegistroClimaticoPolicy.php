<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RegistroClimatico;

class RegistroClimaticoPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view clima records
    }

    public function view(User $user, RegistroClimatico $registroClimatico): bool
    {
        return $user->isAdmin() || $registroClimatico->finca->user_id === $user->id;
    }

    public function update(User $user, RegistroClimatico $registroClimatico): bool
    {
        return $user->isAdmin() || $registroClimatico->finca->user_id === $user->id;
    }

    public function delete(User $user, RegistroClimatico $registroClimatico): bool
    {
        return $user->isAdmin() || $registroClimatico->finca->user_id === $user->id;
    }
}
