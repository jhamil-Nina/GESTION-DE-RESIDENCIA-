@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            👁 Detalle de Usuario
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="space-y-4">

            <div>
                <p class="text-gray-500 text-sm">ID</p>
                <p class="font-semibold">{{ $user->id }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Nombre</p>
                <p class="font-semibold">{{ $user->name }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">CI</p>
                <p class="font-semibold">{{ $user->ci }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Email</p>
                <p class="font-semibold">{{ $user->email }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Teléfono</p>
                <p class="font-semibold">{{ $user->telefono }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Dirección</p>
                <p class="font-semibold">{{ $user->direccion }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Categoría</p>
                <p class="font-semibold">
                    {{ $user->categoriaOcupacion->nombre ?? 'Sin categoría' }}
                </p>
            </div>

        </div>

        <div class="mt-6">

            <a href="{{ route('users.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection