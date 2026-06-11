@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4">

    {{-- BANNER PRINCIPAL --}}
    <div
        class="relative h-72 rounded-3xl overflow-hidden shadow-xl mb-8">

        <img
            src="{{ asset('images/banner-residencia1.jpg') }}"
            class="absolute inset-0 w-full h-full object-cover"
            alt="Banner">

        <div class="absolute inset-0 bg-blue-900/70"></div>

        <div class="relative z-10 h-full flex flex-col justify-center px-10 text-white">

            <h1 class="text-4xl font-bold mb-2">
                Sistema de Gestión de Residencias
            </h1>

            <p class="text-lg opacity-90">
                Panel administrativo de control y seguimiento residencial
            </p>

            <div class="mt-4">
                <span class="bg-white/20 px-4 py-2 rounded-xl">
                    Bienvenido,
                    {{ Auth::user()->name }}
                </span>
            </div>

        </div>
    </div>

    {{-- ACCESOS RÁPIDOS --}}

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <a href="{{ route('users.index') }}"
            class="bg-white rounded-2xl shadow p-6 hover:shadow-xl transition duration-300">

            <p class="text-gray-500 text-sm">
                Residentes
            </p>

            <h2 class="text-4xl font-bold text-blue-600 mt-2">
                {{ $usuarios }}
            </h2>

            <p class="mt-2 text-blue-600 text-sm">
                Ver gestión →
            </p>

        </a>


        <a href="{{ route('habitacions.index') }}"
            class="bg-white rounded-2xl shadow p-6 hover:shadow-xl transition duration-300">

            <p class="text-gray-500 text-sm">
                Habitaciones
            </p>

            <h2 class="text-4xl font-bold text-green-600 mt-2">
                {{ $habitaciones }}
            </h2>

            <p class="mt-2 text-green-600 text-sm">
                Ver gestión →
            </p>

        </a>


        <a href="{{ route('registro_residencias.index') }}"
            class="bg-white rounded-2xl shadow p-6 hover:shadow-xl transition duration-300">

            <p class="text-gray-500 text-sm">
                Registros
            </p>

            <h2 class="text-4xl font-bold text-yellow-600 mt-2">
                {{ $registros }}
            </h2>

            <p class="mt-2 text-yellow-600 text-sm">
                Ver gestión →
            </p>

        </a>


        <a href="{{ route('pagos.index') }}"
            class="bg-white rounded-2xl shadow p-6 hover:shadow-xl transition duration-300">

            <p class="text-gray-500 text-sm">
                Pagos
            </p>

            <h2 class="text-4xl font-bold text-red-600 mt-2">
                {{ $pagos }}
            </h2>

            <p class="mt-2 text-red-600 text-sm">
                Ver gestión →
            </p>

        </a>

    </div>

    {{-- OCUPACIÓN --}}
    <a href="{{ route('habitacions.index') }}"
        class="block bg-white rounded-2xl shadow p-6 mb-8 hover:shadow-xl transition duration-300">

        <div class="flex justify-between items-center mb-2">

            <h3 class="font-semibold text-lg">
                Ocupación General
            </h3>

            <span class="font-bold text-blue-600">
                {{ $porcentajeOcupacion }}%
            </span>

        </div>

        <div class="w-full bg-gray-200 rounded-full h-4">

            <div
                class="bg-blue-600 h-4 rounded-full"
                style="width: {{ $porcentajeOcupacion . '%' }};">
            </div>

        </div>

        <div class="mt-4 flex justify-between text-sm text-gray-500">

            <span>
                🛏 Ocupadas: {{ $habitacionesOcupadas }}
            </span>

            <span>
                🏠 Disponibles: {{ $habitacionesDisponibles }}
            </span>

        </div>

        <p class="mt-3 text-sm text-blue-600">
            Ver habitaciones →
        </p>

    </a>


    <div class="grid lg:grid-cols-2 gap-8">

        {{-- ÚLTIMOS INGRESOS --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <h3 class="font-semibold text-lg mb-4">
                Últimos Ingresos
            </h3>

            <div class="space-y-4">

                @forelse($ultimosIngresos as $registro)

                <div class="border-b pb-3">

                    <p class="font-semibold">
                        {{ $registro->user->name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Habitación {{ $registro->habitacion->numero }}
                    </p>

                </div>

                @empty

                <p>No existen registros.</p>

                @endforelse

            </div>

        </div>


        {{-- ACCIONES RÁPIDAS --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <h3 class="font-semibold text-lg mb-4">
                Acciones Rápidas
            </h3>

            <div class="grid grid-cols-2 gap-4">

                <a href="{{ route('users.create') }}"
                    class="bg-blue-600 text-white p-4 rounded-xl text-center hover:bg-blue-700">
                    Nuevo Residente
                </a>

                <a href="{{ route('pagos.create') }}"
                    class="bg-green-600 text-white p-4 rounded-xl text-center hover:bg-green-700">
                    Registrar Pago
                </a>

                <a href="{{ route('habitacions.create') }}"
                    class="bg-purple-600 text-white p-4 rounded-xl text-center hover:bg-purple-700">
                    Nueva Habitación
                </a>

                <a href="{{ route('observacions.create') }}"
                    class="bg-orange-600 text-white p-4 rounded-xl text-center hover:bg-orange-700">
                    Nueva Observación
                </a>

                <a href="{{ route('registro_residencias.create') }}"
                    class="bg-indigo-600 text-white p-4 rounded-xl text-center hover:bg-indigo-700">
                    Registrar Residencia
                </a>

                <a href="{{ route('antecedentes.create') }}"
                    class="bg-red-600 text-white p-4 rounded-xl text-center hover:bg-red-700">
                    Registrar Antecedente
                </a>

            </div>

        </div>
    </div>

</div>

@endsection