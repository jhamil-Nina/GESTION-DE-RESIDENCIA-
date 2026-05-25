@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏ Editar Registro
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('registro_residencias.update',$registro->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="font-semibold block mb-2">Fecha Ingreso</label>

                <input
                    type="date"
                    name="fecha_ingreso"
                    value="{{ $registro->fecha_ingreso }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>


            <div class="mb-6">

                <label class="font-semibold block mb-2">Fecha Salida</label>

                <input
                    type="date"
                    name="fecha_salida"
                    value="{{ $registro->fecha_salida }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('registro_residencias.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                    Actualizar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection