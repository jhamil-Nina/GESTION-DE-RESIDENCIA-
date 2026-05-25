@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Lista de Antecedentes</h1>

    <a href="{{ route('antecedentes.create') }}"
        class="bg-green-500 text-white px-4 py-2 rounded">
        Nuevo Antecedente
    </a>

    <table class="w-full mt-6 bg-white shadow rounded">

        <thead class="bg-gray-200">

            <tr>

                <th class="p-3">ID</th>
                <th class="p-3">Usuario</th>
                <th class="p-3">Descripción</th>
                <th class="p-3">Fecha</th>
                <th class="p-3">Acciones</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($antecedentes as $antecedente)

            <tr class="border-b">

                <td class="p-3">{{ $antecedente->id }}</td>
                <td class="p-3">{{ $antecedente->user->name }}</td>
                <td class="p-3">{{ $antecedente->descripcion }}</td>
                <td class="p-3">{{ $antecedente->fecha }}</td>

                <td class="px-4 py-3">

                    <div class="flex items-center justify-center gap-2">

                        {{-- VER --}}
                        <a href="{{ route('antecedentes.show', $antecedente) }}"
                            class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600
                        text-white px-4 py-2 rounded-lg shadow-md
                        transition duration-200 text-sm font-medium">

                             Ver
                        </a>

                        {{-- EDITAR --}}
                        <a href="{{ route('antecedentes.edit', $antecedente) }}"
                            class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600
                            text-white px-4 py-2 rounded-lg shadow-md
                            transition duration-200 text-sm font-medium">

                             Editar
                        </a>

                        {{-- ELIMINAR --}}
                        <form action="{{ route('antecedentes.destroy', $antecedente) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('¿Eliminar antecedente?')"
                                class="flex items-center gap-1 bg-red-500 hover:bg-red-600
                                text-white px-4 py-2 rounded-lg shadow-md
                                transition duration-200 text-sm font-medium">

                                 Eliminar
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection 