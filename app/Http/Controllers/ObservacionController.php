<?php

namespace App\Http\Controllers;

use App\Models\Observacion;
use App\Models\RegistroResidencia;
use Illuminate\Http\Request;

class ObservacionController extends Controller
{

    // LISTAR
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $observaciones = Observacion::with('registroResidencia.user')

            ->when($buscar, function ($query, $buscar) {

                $query->where('descripcion', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            ->orderBy('id', 'desc')
            ->get();

        return view('observacions.index', compact('observaciones', 'buscar'));
    }



    // FORMULARIO CREAR
    public function create()
    {
        $registros = RegistroResidencia::with('user')->get();

        return view('observacions.create', compact('registros'));
    }



    // GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'registro_residencia_id' => 'required|exists:registro_residencias,id',
            'descripcion' => 'required|string',
            'fecha' => 'required|date'
        ]);

        Observacion::create([
            'registro_residencia_id' => $request->registro_residencia_id,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha
        ]);

        return redirect()->route('observacions.index')
            ->with('success', 'Observación registrada correctamente');
    }



    // MOSTRAR
    public function show($id)
    {
        $observacion = Observacion::with('registroResidencia.user')
            ->findOrFail($id);

        return view('observacions.show', compact('observacion'));
    }



    // FORMULARIO EDITAR
    public function edit($id)
    {
        $observacion = Observacion::findOrFail($id);

        $registros = RegistroResidencia::with('user')->get();

        return view('observacions.edit', compact('observacion', 'registros'));
    }



    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        $request->validate([
            'registro_residencia_id' => 'required|exists:registro_residencias,id',
            'descripcion' => 'required|string',
            'fecha' => 'required|date'
        ]);

        $observacion = Observacion::findOrFail($id);

        $observacion->update([
            'registro_residencia_id' => $request->registro_residencia_id,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha
        ]);

        return redirect()->route('observacions.index')
            ->with('success', 'Observación actualizada correctamente');
    }



    // ELIMINAR
    public function destroy($id)
    {
        $observacion = Observacion::findOrFail($id);

        $observacion->delete();

        return redirect()->route('observacions.index')
            ->with('success', 'Observación eliminada correctamente');
    }
} 
