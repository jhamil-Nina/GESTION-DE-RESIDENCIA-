<?php

namespace App\Http\Controllers;

use App\Models\CategoriaOcupacion;
use Illuminate\Http\Request;

class CategoriaOcupacionController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $categorias = CategoriaOcupacion::query()

            ->when($buscar, function ($query, $buscar) {

                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            ->orderBy('id', 'desc')

            ->get();

        return view('categoria_ocupacions.index', compact('categorias', 'buscar'));
    }


    public function create()
    {
        return view('categoria_ocupacions.create');
    }

    // GUARDAR
    public function store(Request $request)
    {
        // VALIDACION
        $request->validate([
            'nombre' => 'required|string|max:100'
        ]);

        CategoriaOcupacion::create([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('categoria_ocupacions.index')
            ->with('success', 'Categoria creada correctamente');
    }


    public function show(int $id)
    {
        $categoria = CategoriaOcupacion::findOrFail($id);

        return view('categoria_ocupacions.show', compact('categoria'));
    }


    public function edit(int $id)
    {
        $categoria = CategoriaOcupacion::findOrFail($id);

        return view('categoria_ocupacions.edit', compact('categoria'));
    }


    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100'
        ]);

        $categoria = CategoriaOcupacion::findOrFail($id);

        $categoria->update([
            'nombre' => $request->nombre
        ]);

        return redirect()->route('categoria_ocupacions.index')
            ->with('success', 'Categoria actualizada');
    }


    public function destroy(int $id)
    {
        $categoria = CategoriaOcupacion::findOrFail($id);

        $categoria->delete();

        return redirect()->route('categoria_ocupacions.index')
            ->with('success', 'Categoria eliminada');
    }
} 
