<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habitacion;
use App\Models\RegistroResidencia;
use App\Models\Pago;


class DashboardController extends Controller
{
    public function index()
    {
        // TARJETAS PRINCIPALES
        $usuarios = User::count();
        $habitaciones = Habitacion::count();
        $registros = RegistroResidencia::count();
        $pagos = Pago::count();

        // OCUPACIÓN
        $habitacionesOcupadas = Habitacion::where('estado', 'Ocupada')
            ->count();

        $habitacionesDisponibles = Habitacion::where('estado', 'Disponible')
            ->count();

        $porcentajeOcupacion = $habitaciones > 0
            ? round(($habitacionesOcupadas / $habitaciones) * 100)
            : 0;

        // ÚLTIMOS INGRESOS
        $ultimosIngresos = RegistroResidencia::with([
            'user',
            'habitacion'
        ])
            ->latest()
            ->take(5)
            ->get();

        // ÚLTIMOS PAGOS
        $ultimosPagos = Pago::with([
            'registroResidencia.user'
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'usuarios',
            'habitaciones',
            'registros',
            'pagos',
            'habitacionesOcupadas',
            'habitacionesDisponibles',
            'porcentajeOcupacion',
            'ultimosIngresos',
            'ultimosPagos'
        ));
    }
}
