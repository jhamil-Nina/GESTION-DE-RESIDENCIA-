<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroResidencia;
use App\Models\User;
use App\Models\Habitacion;
use App\Models\CategoriaOcupacion;
use App\Models\Antecedente;

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
        $users = User::all();
        $habitacions = Habitacion::all();
        $categorias = CategoriaOcupacion::all();

        return view('registro_residencias.create', compact(
            'users',
            'habitacions',
            'categorias'
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

        // Verificar si el usuario tiene antecedentes
        $tieneAntecedente = Antecedente::where('user_id', $request->user_id)->exists();

        if ($tieneAntecedente) {
            return redirect()
                ->back()
                ->with('error', 'Este residente tiene antecedentes y no puede registrarse.');
        }

        // Registrar solo si no tiene antecedentes
        RegistroResidencia::create($request->all());

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Registro creado correctamente');
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

        $registro->update($request->all());

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

        $registro->delete();

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Registro eliminado correctamente');
    }
}
 