<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
|
| Aquí controlamos:
| - Login (guest)
| - Registro (guest)
| - Logout (auth)
|
| Todo queda organizado y seguro.
|
*/

Route::middleware('guest')->group(function () {

    /** ============================
     *  LOGIN
     *  ============================ */
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);


    /** ============================
     *  REGISTRO DE USUARIOS
     *  ============================ */
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);
});


/** ============================
 *  LOGOUT
 *  ============================ */
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
