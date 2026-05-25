@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Panel de Administración
        </h1>

        <p class="text-gray-500">
            Gestión general del sistema de residencias
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- USUARIOS -->
        <a href="{{ route('users.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-blue-500">

            <p class="text-gray-500 text-sm">Usuarios</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $usuarios }}
            </h2>

            <p class="text-blue-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- HABITACIONES -->
        <a href="{{ route('habitacions.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-green-500">

            <p class="text-gray-500 text-sm">Habitaciones</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $habitaciones }}
            </h2>

            <p class="text-green-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- RESIDENCIAS -->
        <a href="{{ route('residencias.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-purple-500">

            <p class="text-gray-500 text-sm">Residencias</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $residencias }}
            </h2>

            <p class="text-purple-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- REGISTROS -->
        <a href="{{ route('registro_residencias.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-yellow-500">

            <p class="text-gray-500 text-sm">Registros</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $registros }}
            </h2>

            <p class="text-yellow-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- PAGOS -->
        <a href="{{ route('pagos.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-emerald-500">

            <p class="text-gray-500 text-sm">Pagos</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $pagos }}
            </h2>

            <p class="text-emerald-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- OBSERVACIONES -->
        <a href="{{ route('observacions.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-red-500">

            <p class="text-gray-500 text-sm">Observaciones</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $observaciones }}
            </h2>

            <p class="text-red-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- ANTECEDENTES -->
        <a href="{{ route('antecedentes.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-indigo-500">

            <p class="text-gray-500 text-sm">Antecedentes</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $antecedentes }}
            </h2>

            <p class="text-indigo-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>


        <!-- CATEGORIAS -->
        <a href="{{ route('categoria_ocupacions.index') }}"
            class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition border-l-4 border-pink-500">

            <p class="text-gray-500 text-sm">Categorías</p>

            <h2 class="text-3xl font-bold text-gray-800 mt-2">
                {{ $categorias }}
            </h2>

            <p class="text-pink-600 text-sm mt-2">
                Ver gestión →
            </p>

        </a>

    </div>

</div>

@endsection