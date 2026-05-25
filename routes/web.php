<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// CONTROLADORES DEL CRUD
use App\Http\Controllers\CategoriaOcupacionController;
use App\Http\Controllers\ResidenciaController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistroResidenciaController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AntecedenteController;
use App\Http\Controllers\DashboardController;

// RUTAS WEB

Route::get('/', function () {

    return view('welcome');
});


// CRUD DE CATEGORIA OCUPACION
Route::resource(
    'categoria_ocupacions',
    CategoriaOcupacionController::class
);


// CRUD DE RESIDENCIA
Route::resource(
    'residencias',
    ResidenciaController::class
);


// CRUD DE HABITACION
Route::resource(
    'habitacions',
    HabitacionController::class
);


// CRUD DE USERS
Route::resource(
    'users',
    UserController::class
);


// CRUD DE REGISTRO RESIDENCIA
Route::resource(
    'registro_residencias',
    RegistroResidenciaController::class
);


// CRUD DE OBSERVACIONES
Route::resource(
    'observacions',
    ObservacionController::class
);


// CRUD DE PAGOS
Route::resource(
    'pagos',
    PagoController::class
);


// CRUD DE ANTECEDENTES
Route::resource(
    'antecedentes',
    AntecedenteController::class
);


// DASHBOARD PANEL PRINCIPAL
Route::get('/dashboard', [DashboardController::class, 'index'])

    ->middleware(['auth', 'verified'])

    ->name('dashboard');


// RUTAS PROTEGIDAS POR AUTENTICACION
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


// RUTAS DE AUTENTICACION DE BREEZE
require __DIR__ . '/auth.php';
