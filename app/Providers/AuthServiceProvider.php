<?php

namespace App\Providers;

use App\Models\Cultivo;
use App\Models\Ganado;
use App\Models\Maquinaria;
use App\Models\RegistroClimatico;
use App\Models\PrediccionSemilla;
use App\Policies\CultivoPolicy;
use App\Policies\GanadoPolicy;
use App\Policies\MaquinariaPolicy;
use App\Policies\RegistroClimaticoPolicy;
use App\Policies\PrediccionSemillaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cultivo::class => CultivoPolicy::class,
        Ganado::class => GanadoPolicy::class,
        Maquinaria::class => MaquinariaPolicy::class,
        RegistroClimatico::class => RegistroClimaticoPolicy::class,
        PrediccionSemilla::class => PrediccionSemillaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
