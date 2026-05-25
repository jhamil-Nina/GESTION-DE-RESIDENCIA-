<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habitacion;
use App\Models\Residencia;
use App\Models\RegistroResidencia;
use App\Models\Pago;
use App\Models\Observacion;
use App\Models\Antecedente;
use App\Models\CategoriaOcupacion;

class DashboardController extends Controller
{
    public function index()
    {
        $usuarios = User::count();
        $habitaciones = Habitacion::count();
        $residencias = Residencia::count();
        $registros = RegistroResidencia::count();
        $pagos = Pago::count();
        $observaciones = Observacion::count();
        $antecedentes = Antecedente::count();
        $categorias = CategoriaOcupacion::count();

        return view('dashboard', compact(
            'usuarios',
            'habitaciones',
            'residencias',
            'registros',
            'pagos',
            'observaciones',
            'antecedentes',
            'categorias'
        ));
    }
}
