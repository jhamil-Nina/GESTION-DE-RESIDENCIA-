<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Residencia;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{

    // LISTAR
    public function index(Request $request)
    {
        // Captura búsqueda
        $buscar = $request->buscar;

        // Consulta
        $habitacions = Habitacion::with('residencia')

            ->when($buscar, function ($query, $buscar) {

                $query->where('numero', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            ->orderBy('id', 'desc')

            ->get();

        return view('habitacions.index', compact('habitacions', 'buscar'));
    }


    // FORMULARIO CREAR
    public function create()
    {
        // Obtener residencias para el select
        $residencias = Residencia::all();

        return view('habitacions.create', compact('residencias'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'numero' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'residencia_id' => 'required|exists:residencias,id'
        ]);


        // BUSCAR RESIDENCIA
        $residencia = Residencia::findOrFail($request->residencia_id);


        // SUMAR CAPACIDADES ACTUALES
        $capacidadActual = $residencia->habitacions->sum('capacidad');


        // NUEVA SUMA TOTAL
        $totalCapacidad = $capacidadActual + $request->capacidad;


        // VALIDAR LIMITE
        if ($totalCapacidad > $residencia->capacidad) {

            return redirect()->back()

                ->withInput()

                ->with(
                    'error',
                    'La capacidad excede el límite permitido de la residencia'
                );
        }


        // GUARDAR
        Habitacion::create([
            'numero' => $request->numero,
            'capacidad' => $request->capacidad,
            'residencia_id' => $request->residencia_id,
        ]);


        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación creada correctamente');
    }


    // MOSTRAR
    public function show($id)
    {
        $habitacion = Habitacion::with('residencia')
            ->findOrFail($id);

        return view('habitacions.show', compact('habitacion'));
    }


    // FORMULARIO EDITAR
    public function edit($id)
    {
        $habitacion = Habitacion::findOrFail($id);

        // Residencias para el select
        $residencias = Residencia::all();

        return view('habitacions.edit', compact('habitacion', 'residencias'));
    }


    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        // VALIDACIONES
        $request->validate([
            'numero' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'residencia_id' => 'required|exists:residencias,id'
        ]);

        // BUSCAR
        $habitacion = Habitacion::findOrFail($id);

        // ACTUALIZAR
        $habitacion->update([
            'numero' => $request->numero,
            'capacidad' => $request->capacidad,
            'residencia_id' => $request->residencia_id,
        ]);

        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación actualizada correctamente');
    }


    // ELIMINAR
    public function destroy($id)
    {
        // BUSCAR
        $habitacion = Habitacion::findOrFail($id);

        // ELIMINAR
        $habitacion->delete();

        return redirect()->route('habitacions.index')
            ->with('success', 'Habitación eliminada correctamente');
    }
}
