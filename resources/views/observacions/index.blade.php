@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            📋 Lista de Observaciones
        </h2>

        <p class="text-sm opacity-90">
            Administración de observaciones disciplinarias
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        {{-- MENSAJE --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        {{-- BOTÓN NUEVO --}}
        <div class="flex justify-end mb-6">

            <a href="{{ route('observacions.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nueva Observación

            </a>

        </div>

        {{-- TABLA --}}
        <div class="overflow-x-auto">

            <table class="w-full border rounded-lg overflow-hidden">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="text-left px-4 py-3">ID</th>
                        <th class="text-left px-4 py-3">Usuario</th>
                        <th class="text-left px-4 py-3">Habitación</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-center px-4 py-3">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($observaciones as $observacion)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-4 py-3">
                            {{ $observacion->id }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $observacion->registroResidencia->user->name }}
                        </td>

                        <td class="px-4 py-3">
                            Habitación {{ $observacion->registroResidencia->habitacion->numero }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $observacion->fecha }}
                        </td>

                        <td class="px-4 py-3">

                            <div class="flex items-center justify-center gap-2">

                                {{-- VER --}}
                                <a href="{{ route('observacions.show', $observacion) }}"
                                    class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 text-sm font-medium">

                                    Ver

                                </a>

                                {{-- EDITAR --}}
                                <a href="{{ route('observacions.edit', $observacion) }}"
                                    class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 text-sm font-medium">

                                    Editar

                                </a>

                                {{-- ELIMINAR --}}
                                <form action="{{ route('observacions.destroy', $observacion) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Eliminar observación?')"
                                        class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-200 text-sm font-medium">

                                        Eliminar

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-6 text-gray-500">

                            No existen observaciones registradas

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4 text-sm text-gray-500">
            Mostrando {{ $observaciones->count() }} observaciones
        </div>

    </div>

</div>

@endsection