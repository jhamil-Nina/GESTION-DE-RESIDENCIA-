<?php

namespace App\Http\Controllers;

use App\Models\Antecedente;
use App\Models\User;
use Illuminate\Http\Request;

class AntecedenteController extends Controller
{
    // LISTAR
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $antecedentes = Antecedente::with('user')

            ->when($buscar, function ($query, $buscar) {
                $query->where('descripcion', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            ->orderBy('id', 'desc')
            ->get();

        return view('antecedentes.index', compact('antecedentes', 'buscar'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        $users = User::all();

        return view('antecedentes.create', compact('users'));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required|date'
        ]);

        Antecedente::create($request->all());

        return redirect()->route('antecedentes.index')
            ->with('success', 'Antecedente registrado correctamente');
    }

    // VER
    public function show(Antecedente $antecedente)
    {
        return view('antecedentes.show', compact('antecedente'));
    }

    // FORM EDITAR
    public function edit(Antecedente $antecedente)
    {
        $users = User::all();

        return view('antecedentes.edit', compact('antecedente', 'users'));
    }

    // ACTUALIZAR
    public function update(Request $request, Antecedente $antecedente)
    {
        $request->validate([
            'user_id' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required|date'
        ]);

        $antecedente->update($request->all());

        return redirect()->route('antecedentes.index')
            ->with('success', 'Antecedente actualizado correctamente');
    }

    // ELIMINAR
    public function destroy(Antecedente $antecedente)
    {
        $antecedente->delete();

        return redirect()->route('antecedentes.index')
            ->with('success', 'Antecedente eliminado correctamente');
    } 
}
