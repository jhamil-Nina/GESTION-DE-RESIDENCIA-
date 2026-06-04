<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CategoriaOcupacion;
use Illuminate\Http\Request;

class UserController extends Controller
{

    // LISTAR
    public function index(Request $request)
    {
        // Capturar búsqueda
        $buscar = $request->buscar;

        // Consulta
        $users = User::with('categoriaOcupacion')

            ->when($buscar, function ($query, $buscar) {

                $query->where('name', 'like', "%{$buscar}%")
                    ->orWhere('ci', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            })

            ->get();

        return view('users.index', compact('users', 'buscar'));
    }


    // FORMULARIO CREAR
    public function create()
    {
        // Obtener categorías para el select
        $categorias = CategoriaOcupacion::all();

        return view('users.create', compact('categorias'));
    }


    // GUARDAR
    public function store(Request $request)
    {
        // VALIDACIONES
        $request->validate([
            'name' => 'required|string|max:255',
            'ci' => 'nullable|string|max:50|unique:users,ci',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'categoria_ocupacion_id' => 'nullable|exists:categoria_ocupacions,id',
            'rol' => 'required|in:admin,residente',
            'password' => 'required|string|min:6',
        ]);

        // CREAR USUARIO
        User::create([
            'name' => $request->name,
            'ci' => $request->ci,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'categoria_ocupacion_id' => $request->categoria_ocupacion_id,
            'rol' => $request->rol,
            'password' => $request->password
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }


    // MOSTRAR
    public function show(int $id)
    {
        $user = User::with('categoriaOcupacion')
            ->findOrFail($id);

        return view('users.show', compact('user'));
    }


    // FORMULARIO EDITAR
    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        // categorías para el select
        $categorias = CategoriaOcupacion::all();

        return view('users.edit', compact('user', 'categorias'));
    }


    // ACTUALIZAR
    public function update(Request $request, int $id)
    {
        // VALIDACIONES
        $request->validate([
            'name' => 'required|string|max:255',
            'ci' => "nullable|string|max:50|unique:users,ci,$id",
            'email' => "required|email|unique:users,email,$id",
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'categoria_ocupacion_id' => 'nullable|exists:categoria_ocupacions,id',
            'rol' => 'required|in:admin,residente',
        ]);

        // BUSCAR
        $user = User::findOrFail($id);

        // ACTUALIZAR
        $user->update([
            'name' => $request->name,
            'ci' => $request->ci,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'categoria_ocupacion_id' => $request->categoria_ocupacion_id,
            'rol' => $request->rol,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }


    // ELIMINAR
    public function destroy(int $id)
    {
        // BUSCAR
        $user = User::findOrFail($id);

        // ELIMINAR
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
} 
