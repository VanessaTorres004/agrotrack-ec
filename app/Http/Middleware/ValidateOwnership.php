<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to validate resource ownership
 * Ensures users can only access resources they own
 */
class ValidateOwnership
{
    public function handle(Request $request, Closure $next, string $model): Response
    {
        $userId = auth()->id();
        
        // Skip for administrators
        if (auth()->user()->rol === 'administrador') {
            return $next($request);
        }

        // Validate based on model type
        switch ($model) {
            case 'finca':
                if ($request->route('finca')) {
                    $finca = $request->route('finca');
                    if ($finca->user_id !== $userId) {
                        abort(403, 'No tiene permiso para acceder a esta finca');
                    }
                }
                break;

            case 'cultivo':
                if ($request->route('cultivo')) {
                    $cultivo = $request->route('cultivo');
                    if ($cultivo->finca->user_id !== $userId) {
                        abort(403, 'No tiene permiso para acceder a este cultivo');
                    }
                }
                break;

            case 'ganado':
                if ($request->route('ganado')) {
                    $ganado = $request->route('ganado');
                    if ($ganado->finca->user_id !== $userId) {
                        abort(403, 'No tiene permiso para acceder a este animal');
                    }
                }
                break;

            case 'maquinaria':
                if ($request->route('maquinaria')) {
                    $maquinaria = $request->route('maquinaria');
                    if ($maquinaria->finca->user_id !== $userId) {
                        abort(403, 'No tiene permiso para acceder a esta maquinaria');
                    }
                }
                break;
        }

        return $next($request);
    }
}

