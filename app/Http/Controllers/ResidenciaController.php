<?php

namespace App\Http\Controllers;

use App\Models\Residencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResidenciaController extends Controller
{

    // LISTAR
    public function index(Request $request)
    {
        // Captura búsqueda
        $buscar = $request->buscar;

        // Consulta
        $residencias = Residencia::query()

            // Buscar por nombre  dirección   id
            ->when($buscar, function ($query, $buscar) {

                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('direccion', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })


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
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'max_habitaciones' => 'required|integer|min:1|max:2147483647',
        ]);

        Residencia::create($datos);

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia creada correctamente');
    }


    // MOSTRAR
    public function show(int $id)
    {
        $residencia = Residencia::findOrFail($id);

        return view('residencias.show', compact('residencia'));
    }


    // FORMULARIO EDITAR
    public function edit(int $id)
    {
        $residencia = Residencia::findOrFail($id);

        return view('residencias.edit', compact('residencia'));
    }


    // ACTUALIZAR
    public function update(Request $request, int $id)
    {
        // VALIDACIONES
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'capacidad' => 'required|integer|min:1',
            'max_habitaciones' => 'required|integer|min:1|max:2147483647',
        ]);

        DB::transaction(function () use ($id, $datos) {
            $residencia = Residencia::lockForUpdate()->findOrFail($id);

            if ($residencia->habitacions()->count() > $datos['max_habitaciones']) {
                throw ValidationException::withMessages([
                    'max_habitaciones' => 'El máximo debe cubrir las habitaciones ya registradas.',
                ]);
            }

            if ($residencia->habitacions()->sum('capacidad') > $datos['capacidad']) {
                throw ValidationException::withMessages([
                    'capacidad' => 'La capacidad debe cubrir las plazas de las habitaciones registradas.',
                ]);
            }

            $residencia->update($datos);
        }, 3);

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia actualizada correctamente');
    }


    // ELIMINAR
    public function destroy(int $id)
    {
        // BUSCAR
        $residencia = Residencia::findOrFail($id);

        // ELIMINAR
        $residencia->delete();

        return redirect()->route('residencias.index')
            ->with('success', 'Residencia eliminada correctamente');
    }
}
