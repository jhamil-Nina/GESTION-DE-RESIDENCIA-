@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            🏠 Registros de Residencia
        </h2>

        <p class="text-sm opacity-90">
            Administración de residentes
        </p>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('success'))

        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>

        @endif


        <div class="flex justify-between items-center mb-6 gap-4">

            <form method="GET"
                action="{{ route('registro_residencias.index') }}"
                class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar residente..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">

                <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">

                    Buscar

                </button>

            </form>


            <a href="{{ route('registro_residencias.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nuevo Registro

            </a>

        </div>


        <table class="w-full border rounded-lg overflow-hidden">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Residente</th>
                    <th class="px-4 py-3 text-left">Habitación</th>
                    <th class="px-4 py-3 text-left">Categoría</th>
                    <th class="px-4 py-3 text-center">Ingreso</th>
                    <th class="px-4 py-3 text-center">Salida</th>
                    <th class="px-4 py-3 text-center">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @forelse($registro_residencias as $registro)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $registro->id }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $registro->user->name }}
                    </td>

                    <td class="px-4 py-3">
                        Habitación {{ $registro->habitacion->numero }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $registro->categoriaOcupacion->nombre }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $registro->fecha_ingreso }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $registro->fecha_salida ?? '—' }}
                    </td>
                    <td class="px-4 py-3">

                        <div class="flex items-center justify-center gap-2">

                            {{-- VER --}}
                            <a href="{{ route('registro_residencias.show', $registro->id) }}"
                                class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 
                   text-white px-4 py-2 rounded-lg shadow-md 
                   transition duration-200 text-sm font-medium">

                                 Ver
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('registro_residencias.edit', $registro->id) }}"
                                class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 
                                text-white px-4 py-2 rounded-lg shadow-md 
                                transition duration-200 text-sm font-medium">

                                 Editar
                            </a>

                            {{-- ELIMINAR --}}
                            <form
                                action="{{ route('registro_residencias.destroy', $registro->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Eliminar registro?')"
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 
                                    text-white px-4 py-2 rounded-lg shadow-md 
                                    transition duration-200 text-sm font-medium">

                                     Eliminar
                                </button>

                            </form>

                        </div>

                    </td>
                </tr>

                @empty

                <tr>

                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No hay registros
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection