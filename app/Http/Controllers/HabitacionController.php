<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Residencia;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HabitacionController extends Controller 
{

    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $residencias = Residencia::with(['habitacions' => function ($query) use ($buscar) {

            if ($buscar) {
                $query->where('numero', 'like', "%{$buscar}%");
            }

            $query->with('tipoHabitacion')->orderBy('numero');
        }])->get();

        return view(
            'habitacions.index',
            compact('residencias', 'buscar')
        );
    }

    // FORMULARIO CREAR
    public function create()
    {
        // Obtener residencias para el select
        $residencias = Residencia::all();
        $tipos = TipoHabitacion::orderBy('nombre')->get();

        return view('habitacions.create', compact('residencias', 'tipos'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $this->guardar($request);

        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación creada correctamente');
    }


    // MOSTRAR
    public function show(int $id)
    {
        $habitacion = Habitacion::with(['residencia', 'tipoHabitacion'])
            ->findOrFail($id);

        return view('habitacions.show', compact('habitacion'));
    }


    // FORMULARIO EDITAR
    public function edit(int $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        // Residencias para el select
        $residencias = Residencia::all();
        $tipos = TipoHabitacion::orderBy('nombre')->get();

        return view('habitacions.edit', compact('habitacion', 'residencias', 'tipos'));
    }


    // ACTUALIZAR
    public function update(Request $request, int $id)
    {
        $this->guardar($request, $id);

        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación actualizada correctamente');
    }


    private function guardar(Request $request, ?int $id = null): void
    {
        $datos = $request->validate([
            'numero' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'residencia_id' => 'required|exists:residencias,id',
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
        ]);

        DB::transaction(function () use ($datos, $id) {
            $residencia = Residencia::lockForUpdate()->findOrFail($datos['residencia_id']);
            $habitacion = $id === null ? new Habitacion() : Habitacion::lockForUpdate()->findOrFail($id);
            $otras = $residencia->habitacions();

            if ($habitacion->exists) {
                $otras->whereKeyNot($habitacion->id);
            }

            if ($residencia->max_habitaciones !== null && $otras->count() >= $residencia->max_habitaciones) {
                throw ValidationException::withMessages([
                    'residencia_id' => 'La residencia alcanzó su máximo de habitaciones.',
                ]);
            }

            if ($otras->sum('capacidad') + $datos['capacidad'] > $residencia->capacidad) {
                throw ValidationException::withMessages([
                    'capacidad' => 'La suma de plazas supera la capacidad de la residencia.',
                ]);
            }

            if (TipoHabitacion::sharedLock()->find($datos['tipo_habitacion_id']) === null) {
                throw ValidationException::withMessages([
                    'tipo_habitacion_id' => 'El tipo seleccionado ya no está disponible.',
                ]);
            }

            $habitacion->fill($datos)->save();
        }, 3);
    }

    // ELIMINAR
    public function destroy(int $id)
    {
        // BUSCAR
        $habitacion = Habitacion::findOrFail($id);

        $habitacion->delete();

        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación eliminada correctamente');
    }
}
