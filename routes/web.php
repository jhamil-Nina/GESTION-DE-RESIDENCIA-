<?php

use Illuminate\Support\Facades\Route; // permite crear rutas en Laravel
use App\Http\Controllers\ProfileController; // controlador del perfil (Breeze)
  
// CONTROLADORES DEL CRUD
use App\Http\Controllers\CategoriaOcupacionController; // controlador de categorias
use App\Http\Controllers\ResidenciaController; // controlador de residencias
use App\Http\Controllers\HabitacionController; // controlador de habitaciones
use App\Http\Controllers\UserController; // controlador de usuarios
use App\Http\Controllers\RegistroResidenciaController; // controlador de registros de residencia
use App\Http\Controllers\ObservacionController; // controlador de observaciones
use App\Http\Controllers\PagoController; // controlador de pagos
use App\Http\Controllers\AntecedenteController; // controlador de antecedentes
use App\Http\Controllers\DashboardController; // controlador del dashboard

// RUTAS WEB

Route::get('/', function () {
    return view('welcome'); // muestra la vista welcome.blade.php
});


//CRUD DE CATEGORIA OCUPACION

Route::resource('categoria_ocupacions', CategoriaOcupacionController::class);

//CRUD DE RESIDENCIA

Route::resource('residencias', ResidenciaController::class);

//CRUD DE HABITACION

Route::resource('habitacions', HabitacionController::class);

// CRUD DE USERS

Route::resource('users', UserController::class);

// CRUD DE REGISTRO RESIDENCIA
Route::resource('registro_residencias', RegistroResidenciaController::class);

// CRUD DE OBSERVACIONES
Route::resource('observacions', ObservacionController::class);

// CRUD DE PAGOS
Route::resource('pagos', PagoController::class);

// CRUD DE ANTECEDENTES
Route::resource('antecedentes', AntecedenteController::class);

//DASHBOARD PANEL PRINCIPAL

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//RUTAS PROTEGIDAS POR AUTENTICACION DEL LOGIN

Route::middleware('auth')->group(function () {

    // editar perfil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // actualizar perfil
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // eliminar perfil
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

//RUTAS DE AUTENTICACION (REGISTRO, LOGIN, ETC) - PROPORCIONADAS POR BREEZE

require __DIR__ . '/auth.php';
