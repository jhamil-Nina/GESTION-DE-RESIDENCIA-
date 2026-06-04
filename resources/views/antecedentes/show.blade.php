@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            👁 Detalle del Antecedente
        </h2>

        <p class="text-sm opacity-90">
            Información completa del antecedente registrado
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="space-y-4">

            <div>
                <p class="text-gray-500 text-sm">ID</p>
                <p class="font-semibold">
                    {{ $antecedente->id }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Usuario</p>
                <p class="font-semibold">
                    {{ $antecedente->user->name }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Fecha</p>
                <p class="font-semibold">
                    {{ $antecedente->fecha }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Descripción</p>

                <div class="mt-1 p-4 bg-gray-50 border rounded-lg">
                    {{ $antecedente->descripcion }}
                </div>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="mt-6 flex gap-3">

            <a href="{{ route('antecedentes.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection