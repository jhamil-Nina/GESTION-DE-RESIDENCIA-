@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nuevo Registro de Residencia
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('registro_residencias.store') }}" method="POST">

            @csrf

            <!-- USUARIO -->
            <div class="mb-4">

                <label class="font-semibold block mb-2">Residente</label>

                <select name="user_id" class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione</option>

                    @foreach($users as $user)

                    <option value="{{ $user->id }}">
                        {{ $user->name }}
                    </option>

                    @endforeach

                </select>

            </div>


            <!-- HABITACION -->
            <div class="mb-4">

                <label class="font-semibold block mb-2">Habitación</label>

                <select name="habitacion_id" class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione</option>

                    @foreach($habitacions as $habitacion)

                    <option value="{{ $habitacion->id }}">
                        Habitación {{ $habitacion->numero }}
                    </option>

                    @endforeach

                </select>

            </div>


            <!-- CATEGORIA -->
            <div class="mb-4">

                <label class="font-semibold block mb-2">Categoría</label>

                <select name="categoria_ocupacion_id" class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione</option>

                    @foreach($categorias as $categoria)

                    <option value="{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </option>

                    @endforeach

                </select>

            </div>


            <div class="mb-4">

                <label class="font-semibold block mb-2">Fecha Ingreso</label>

                <input
                    type="date"
                    name="fecha_ingreso"
                    class="w-full border rounded-lg px-4 py-2">

            </div>


            <div class="mb-6">

                <label class="font-semibold block mb-2">Fecha Salida</label>

                <input
                    type="date"
                    name="fecha_salida"
                    class="w-full border rounded-lg px-4 py-2">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('registro_residencias.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg">

                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection