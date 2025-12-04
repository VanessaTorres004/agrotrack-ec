<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ActualizacionController;
use App\Http\Controllers\CosechaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\RegistroClimaticoController;
use App\Http\Controllers\GanadoController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PrediccionSemillaController;
use App\Http\Controllers\Api\DropdownController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard según rol
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return app(DashboardController::class)->productor();
    })->name('dashboard');


    // ===========================
    // API DROPDOWNS
    // ===========================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/fincas', [DropdownController::class, 'getFincas'])->name('fincas');
        Route::get('/cultivos-by-finca', [DropdownController::class, 'getCultivosByFinca'])->name('cultivos.by-finca');
        Route::get('/ganado-by-finca', [DropdownController::class, 'getGanadoByFinca'])->name('ganado.by-finca');
        Route::get('/ganado-by-tipo', [DropdownController::class, 'getGanadoByTipo'])->name('ganado.by-tipo');
        Route::get('/maquinaria-by-finca', [DropdownController::class, 'getMaquinariaByFinca'])->name('maquinaria.by-finca');
        Route::get('/provincias', [DropdownController::class, 'getProvincias'])->name('provincias');
        Route::get('/cantones-by-provincia', [DropdownController::class, 'getCantonesByProvincia'])->name('cantones.by-provincia');
    });


    // ===========================
    // RUTAS COMPARTIDAS (AMBOS ROLES)
    // ===========================

    // GANADO
    Route::resource('ganado', GanadoController::class);
    Route::get('ganado/{ganado}/vacunas', [VacunaController::class, 'historial'])->name('ganado.vacunas');
    Route::post('ganado/{ganado}/vacunas', [VacunaController::class, 'store'])->name('vacunas.store');
    Route::get('ganado/{ganado}/vacunas/create', [VacunaController::class, 'create'])->name('vacunas.create');
    Route::get('ganado/alertas', [GanadoController::class, 'alertas'])->name('ganado.alertas');

    // PREDICCIONES
    Route::get('predicciones', [PrediccionSemillaController::class, 'index'])->name('predicciones.index');
    Route::post('predicciones/calcular', [PrediccionSemillaController::class, 'calcular'])->name('predicciones.calcular');
    Route::get('predicciones/{prediccion}', [PrediccionSemillaController::class, 'show'])->name('predicciones.show');

    // MAQUINARIA
    Route::resource('maquinaria', MaquinariaController::class);

    Route::get('maquinaria/{maquinaria}/mantenimientos', 
        [MantenimientoController::class, 'historial'])
        ->name('maquinaria.mantenimientos');

    Route::post('maquinaria/{maquinaria}/mantenimientos', 
        [MantenimientoController::class, 'store'])
        ->name('mantenimientos.store');

    Route::get('maquinaria/{maquinaria}/mantenimientos/create', 
        [MantenimientoController::class, 'create'])
        ->name('mantenimientos.create');

    // CLIMA (ambos roles)
    Route::resource('clima', RegistroClimaticoController::class);


    // ===========================
    // PRODUCTOR ÚNICAMENTE
    // ===========================
    Route::middleware(['role:productor'])->group(function () {

        Route::resource('cultivos', CultivoController::class);
        
        // Ruta para recalcular IDC
        Route::post('cultivos/{cultivo}/recalcular-idc', 
            [CultivoController::class, 'recalcularIDC'])
            ->name('cultivos.recalcularIDC');

        Route::resource('actualizaciones', ActualizacionController::class);
        Route::resource('cosechas', CosechaController::class);
        Route::resource('ventas', VentaController::class);
    });


    // ===========================
    // ADMINISTRADOR
    // ===========================
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('/productores', [AdminController::class, 'productores'])
                ->name('productores');

            Route::get('/cultivos', [AdminController::class, 'cultivos'])
                ->name('cultivos');

            Route::get('/reportes', [AdminController::class, 'reportes'])
                ->name('reportes');

            Route::get('/reportes/pdf', [AdminController::class, 'exportarPDF'])
                ->name('reportes.pdf');

            Route::get('/reportes/csv', [AdminController::class, 'exportarCSV'])
                ->name('reportes.csv');
        });
    
});

require __DIR__.'/auth.php';
