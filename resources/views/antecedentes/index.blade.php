@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            📜 Lista de Antecedentes
        </h2>

        <p class="text-sm opacity-90">
            Administración de antecedentes de los residentes
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

            <a href="{{ route('antecedentes.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nuevo Antecedente

            </a>

        </div>

        {{-- TABLA --}}
        <div class="overflow-x-auto">

            <table class="w-full border rounded-lg overflow-hidden">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="text-left px-4 py-3">ID</th>
                        <th class="text-left px-4 py-3">Usuario</th>
                        <th class="text-left px-4 py-3">Descripción</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-center px-4 py-3">Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($antecedentes as $antecedente)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-4 py-3">
                            {{ $antecedente->id }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $antecedente->user->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ Str::limit($antecedente->descripcion, 50) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $antecedente->fecha }}
                        </td>

                        <td class="px-4 py-3">

                            <div class="flex items-center justify-center gap-2">

                                {{-- VER --}}
                                <a href="{{ route('antecedentes.show', $antecedente) }}"
                                    class="w-24 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow">

                                    Ver

                                </a>

                                {{-- EDITAR --}}
                                <a href="{{ route('antecedentes.edit', $antecedente) }}"
                                    class="w-24 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg shadow">

                                    Editar

                                </a>

                                {{-- ELIMINAR --}}
                                <form
                                    action="{{ route('antecedentes.destroy', $antecedente) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Eliminar antecedente?')"
                                        class="w-24 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg shadow">

                                        Eliminar

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center py-6 text-gray-500">

                            No existen antecedentes registrados

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4 text-sm text-gray-500">
            Mostrando {{ $antecedentes->count() }} antecedentes
        </div>

    </div>

</div>

@endsection