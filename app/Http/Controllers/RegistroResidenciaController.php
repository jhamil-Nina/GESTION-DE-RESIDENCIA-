<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroResidencia;
use App\Models\User;
use App\Models\Habitacion;
use App\Models\CategoriaOcupacion;
use App\Models\Antecedente;
use App\Models\Residencia;


class RegistroResidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $registro_residencias = RegistroResidencia::with([
            'user',
            'habitacion',
            'categoriaOcupacion'
        ])
            ->when($buscar, function ($query, $buscar) {
                $query->whereHas('user', function ($q) use ($buscar) {
                    $q->where('name', 'like', "%$buscar%");
                });
            })
            ->latest()
            ->get();

        return view('registro_residencias.index', compact(
            'registro_residencias',
            'buscar'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::with('categoriaOcupacion')->get();

        $residencias = Residencia::all();

        $habitacions = Habitacion::with('residencia')
            ->where('estado', 'Disponible')
            ->get();

        return view('registro_residencias.create', compact(
            'users',
            'residencias',
            'habitacions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'habitacion_id' => 'required',
            'categoria_ocupacion_id' => 'required',
            'fecha_ingreso' => 'required|date',
            'fecha_salida' => 'nullable|date'
        ]);

        // Verificar antecedentes
        $tieneAntecedente = Antecedente::where(
            'user_id',
            $request->user_id
        )->exists();

        if ($tieneAntecedente) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Este residente tiene antecedentes y no puede registrarse.'
                );
        }

        // Buscar habitación
        $habitacion = Habitacion::findOrFail(
            $request->habitacion_id
        );

        // Contar ocupantes actuales
        $ocupantesActuales = RegistroResidencia::where(
            'habitacion_id',
            $habitacion->id
        )
            ->where(function ($query) {
                $query->whereNull('fecha_salida')
                    ->orWhere('fecha_salida', '>=', now()->toDateString());
            })
            ->count();


        // Validar capacidad
        if ($ocupantesActuales >= $habitacion->capacidad) {

            $habitacion->estado = 'Ocupada';
            $habitacion->save();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'La habitación ya alcanzó su capacidad máxima.'
                );
        }

        // Crear registro
        RegistroResidencia::create($request->all());

        $this->actualizarEstadoHabitacion($habitacion->id);

        return redirect()
            ->route('registro_residencias.index')
            ->with(
                'success',
                'Registro creado correctamente.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registro = RegistroResidencia::with([
            'user',
            'habitacion',
            'categoriaOcupacion'
        ])->findOrFail($id);

        return view('registro_residencias.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $registro = RegistroResidencia::findOrFail($id);

        $users = User::all();
        $habitacions = Habitacion::all();
        $categorias = CategoriaOcupacion::all();

        return view('registro_residencias.edit', compact(
            'registro',
            'users',
            'habitacions',
            'categorias'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
            'habitacion_id' => 'required',
            'categoria_ocupacion_id' => 'required',
            'fecha_ingreso' => 'required|date',
            'fecha_salida' => 'nullable|date'
        ]);

        $registro = RegistroResidencia::findOrFail($id);

        // Guardar habitación anterior
        $habitacionAnterior = $registro->habitacion_id;

        $registro->update($request->all());

        // Actualizar estado de ambas habitaciones
        $this->actualizarEstadoHabitacion($habitacionAnterior);
        $this->actualizarEstadoHabitacion($registro->habitacion_id);

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $registro = RegistroResidencia::findOrFail($id);

        $habitacionId = $registro->habitacion_id;

        $registro->delete();

        $this->actualizarEstadoHabitacion($habitacionId);

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Registro eliminado correctamente');
    }

    private function actualizarEstadoHabitacion(int|string $habitacionId)
    {
        $habitacion = Habitacion::find($habitacionId);

        if (!$habitacion) {
            return;
        }

        $ocupantesActuales = RegistroResidencia::where(
            'habitacion_id',
            $habitacion->id
        )
            ->where(function ($query) {
                $query->whereNull('fecha_salida')
                    ->orWhere('fecha_salida', '>=', now()->toDateString());
            })
            ->count();

        $habitacion->estado = $ocupantesActuales >= $habitacion->capacidad
            ? 'Ocupada'
            : 'Disponible';

        $habitacion->save();
    }
}
 