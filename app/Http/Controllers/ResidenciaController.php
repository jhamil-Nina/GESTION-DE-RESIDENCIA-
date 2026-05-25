<?php

namespace App\Http\Controllers;

use App\Models\Residencia;
use Illuminate\Http\Request;

class ResidenciaController extends Controller
{

    // LISTAR
    public function index(Request $request)
    {
        // Captura búsqueda
        $buscar = $request->buscar;

        // Consulta
        $residencias = Residencia::query()

            // Buscar por nombre o dirección
            ->when($buscar, function ($query, $buscar) {

                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('direccion', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            // Orden descendente
            ->orderBy('id', 'desc')

            // Obtener registros
            ->get();

        return view('residencias.index', compact('residencias', 'buscar'));
    }


    // FORMULARIO CREAR
    public function create()
    {
        return view('residencias.create');
    }


    // GUARDAR
    public function store(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1'
        ]);

        // GUARDAR
        Residencia::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'capacidad' => $request->capacidad,
        ]);

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia creada correctamente');
    }


    // MOSTRAR
    public function show($id)
    {
        $residencia = Residencia::findOrFail($id);

        return view('residencias.show', compact('residencia'));
    }


    // FORMULARIO EDITAR
    public function edit($id)
    {
        $residencia = Residencia::findOrFail($id);

        return view('residencias.edit', compact('residencia'));
    }


    // ACTUALIZAR
    public function update(Request $request, $id)
    {
        // VALIDACIONES
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1'
        ]);

        // BUSCAR
        $residencia = Residencia::findOrFail($id);

        // ACTUALIZAR
        $residencia->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'capacidad' => $request->capacidad,
        ]);

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia actualizada correctamente');
    }


    // ELIMINAR
    public function destroy($id)
    {
        // BUSCAR
        $residencia = Residencia::findOrFail($id);

        // ELIMINAR
        $residencia->delete();

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia eliminada correctamente');
    }
}
