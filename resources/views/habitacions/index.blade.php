{{-- resources/views/habitacions/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="module-heading p-6 rounded-t-xl ">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            🚪 Lista de Habitaciones
        </h2>

        <p class="text-sm opacity-90">
            Administra las habitaciones del sistema
        </p>

    </div>

    <!-- CARD -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <!-- MENSAJE -->
        @if(session('success'))

        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>

        @endif


        <!-- BUSCADOR -->
        <div class="flex justify-between items-center mb-6 gap-4">

            <form
                method="GET"
                action="{{ route('habitacions.index') }}"
                class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar habitación..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 ">

                <button
                    type="submit"
                    class="module-secondary px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <!-- BOTON -->
            <a href="{{ route('tipos_habitacion.index') }}" class="px-4 py-2 text-blue-700">
                Tipos de habitación
            </a>

            <a href="{{ route('habitacions.create') }}"
                class="module-primary px-5 py-2 rounded-lg shadow">

                + Nueva Habitación

            </a>

        </div>


        @forelse($residencias as $residencia)

        <div class="mb-8 border rounded-xl overflow-hidden shadow-sm">

            <!-- CABECERA -->
            <div class="bg-indigo-100 px-6 py-4 flex justify-between items-center">

                <h3 class="text-xl font-bold text-indigo-700">
                     {{ $residencia->nombre }}
                </h3>

                <span class="module-primary px-3 py-1 rounded-full text-sm">
                    {{ $residencia->habitacions->count() }} habitaciones
                </span>

            </div>

            @if($residencia->habitacions->count())

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr>
                        <th class="px-4 py-3 text-left">Número</th>
                        <th class="px-4 py-3 text-center">Tipo</th>
                        <th class="px-4 py-3 text-center">Capacidad</th>
                        <th class="px-4 py-3 text-center">Costo</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($residencia->habitacions as $habitacion)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-4 py-3">
                            {{ $habitacion->numero }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{ $habitacion->tipoHabitacion?->nombre ?? 'Sin asignar' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            {{ $habitacion->capacidad }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            Bs {{ number_format($habitacion->costo_mensual, 2) }}
                        </td>

                        <td class="px-4 py-3 text-center">

                            @if($habitacion->estado == 'Disponible')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Disponible
                            </span>

                            @elseif($habitacion->estado == 'Ocupada')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Ocupada
                            </span>

                            @endif

                        </td>

                        <td class="px-4 py-3">

                            <div class="flex flex-wrap justify-center items-center gap-2">

                                <a href="{{ route('habitacions.show', $habitacion->id) }}"
                                    class="w-24 h-10 flex items-center justify-center module-secondary rounded-lg">
                                    Ver
                                </a>

                                <a href="{{ route('habitacions.edit', $habitacion->id) }}"
                                    class="w-24 h-10 flex items-center justify-center module-accent rounded-lg">
                                    Editar
                                </a>

                                <form
                                    action="{{ route('habitacions.destroy', $habitacion->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta habitación?')"
                                        class="w-24 h-10 flex items-center justify-center module-danger rounded-lg">

                                        Eliminar

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            @else

            <div class="p-6 text-center text-gray-500">
                No hay habitaciones registradas en esta residencia.
            </div>

            @endif

        </div>

        @empty

        <div class="text-center py-8 text-gray-500">
            No existen residencias registradas.
        </div>

        @endforelse


        @php
        $totalHabitaciones = $residencias->sum(function($residencia){
        return $residencia->habitacions->count();
        });
        @endphp


        <!-- FOOTER -->

        <div class="mt-4 text-sm text-gray-500">

            Mostrando {{ $totalHabitaciones }} habitaciones en
            {{ $residencias->count() }} residencias

        </div>

    </div>

</div>

@endsection
