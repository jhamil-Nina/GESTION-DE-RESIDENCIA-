<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habitacion;
use App\Models\RegistroResidencia;
use App\Models\Pago;
use App\Models\Mensualidad;
use App\Models\Observacion;
use App\Models\Antecedente;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = today()->toDateString();

        // TARJETAS GENERALES
        $usuarios = User::count();
        $habitaciones = Habitacion::count();
        $registros = RegistroResidencia::count();
        $pagos = Pago::count();

        // CONTRATOS VIGENTES
        $contratosActivos = RegistroResidencia::whereDate(
            'fecha_ingreso', '<=', $hoy
        )
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_salida')
                    ->orWhereDate('fecha_salida', '>', $hoy);
            });

        $residentesActivos = (clone $contratosActivos)
            ->distinct()
            ->count('user_id');

        // HABITACIONES OCUPADAS ACTUALMENTE
        $habitacionesOcupadas = (clone $contratosActivos)
            ->distinct()
            ->count('habitacion_id');

        // HABITACIONES EN MANTENIMIENTO
        $habitacionesMantenimiento = Habitacion::where(
            'estado', 'Mantenimiento'
        )->count();

        // HABITACIONES LIBRES (EXCLUYENDO MANTENIMIENTO)
        $habitacionesDisponibles = Habitacion::where(
            'estado', '!=', 'Mantenimiento'
        )
            ->whereDoesntHave('registrosResidencia', function ($query) use ($hoy) {
                $query->whereDate('fecha_ingreso', '<=', $hoy)
                    ->where(function ($q) use ($hoy) {
                        $q->whereNull('fecha_salida')
                            ->orWhereDate('fecha_salida', '>', $hoy);
                    });
            })
            ->count();

        $porcentajeOcupacion = $habitaciones > 0
            ? round(($habitacionesOcupadas / $habitaciones) * 100)
            : 0;

        // FINANZAS
        $totalRecaudado = Pago::where('estado', 'Pagado')
            ->sum('monto');

        $deudaPendiente = Mensualidad::where(
            'estado', 'Pendiente'
        )->sum('monto');

        $cuotasPendientes = Mensualidad::where(
            'estado', 'Pendiente'
        )->count();

        // OBSERVACIONES
        $observacionesPendientes = Observacion::where(
            'estado', 'Pendiente'
        )->count();

        $observacionesReparacion = Observacion::where(
            'estado', 'En reparación'
        )->count();

        $observacionesSolucionadas = Observacion::where(
            'estado', 'Solucionado'
        )->count();

        // ANTECEDENTES
        $totalAntecedentes = Antecedente::count();

        // ÚLTIMOS INGRESOS
        $ultimosIngresos = RegistroResidencia::with([
            'user',
            'habitacion'
        ])
            ->orderByDesc('fecha_ingreso')
            ->take(5)
            ->get();

        // ÚLTIMOS PAGOS
        $ultimosPagos = Pago::with([
            'registroResidencia.user'
        ])
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'usuarios',
            'habitaciones',
            'registros',
            'pagos',
            'residentesActivos',
            'habitacionesOcupadas',
            'habitacionesDisponibles',
            'habitacionesMantenimiento',
            'porcentajeOcupacion',
            'totalRecaudado',
            'deudaPendiente',
            'cuotasPendientes',
            'observacionesPendientes',
            'observacionesReparacion',
            'observacionesSolucionadas',
            'totalAntecedentes',
            'ultimosIngresos',
            'ultimosPagos'
        ));
    }
}
