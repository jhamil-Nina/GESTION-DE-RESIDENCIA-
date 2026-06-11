@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nuevo Registro de Residencia
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('error'))

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">

            <strong>Error:</strong>
            {{ session('error') }}

        </div>

        @endif

        <form action="{{ route('registro_residencias.store') }}" method="POST">

            @csrf

            <!-- USUARIO -->
            <div class="mb-4">

                <label class="font-semibold block mb-2">Residente</label>

                <select name="user_id" class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione</option>

                    @foreach($users as $user)

                    <option
                        value="{{ $user->id }}"
                        data-categoria-id="{{ $user->categoria_ocupacion_id }}"
                        data-categoria="{{ $user->categoriaOcupacion->nombre ?? '' }}">

                        {{ $user->name }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="font-semibold block mb-2">
                    Residencia
                </label>

                <select
                    id="residencia_id"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">
                        Seleccione una residencia
                    </option>

                    @foreach($residencias as $residencia)

                    <option value="{{ $residencia->id }}">
                        {{ $residencia->nombre }}
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

                    <option
                        value="{{ $habitacion->id }}"
                        data-residencia="{{ $habitacion->residencia_id }}">

                        Habitación {{ $habitacion->numero }}

                    </option>

                    @endforeach

                </select>

            </div>


            <!-- CATEGORIA -->
            <div class="mb-4">

                <label class="font-semibold block mb-2">Categoría</label>

                <div class="mb-4">

                    <input
                        type="text"
                        id="categoria_nombre"
                        class="w-full border rounded-lg px-4 py-2 bg-gray-100"
                        readonly>

                    <input
                        type="hidden"
                        name="categoria_ocupacion_id"
                        id="categoria_id">

                </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const usuario = document.querySelector('[name="user_id"]');

        const categoriaNombre =
            document.getElementById('categoria_nombre');

        const categoriaId =
            document.getElementById('categoria_id');

        usuario.addEventListener('change', function() {

            const opcion =
                this.options[this.selectedIndex];

            categoriaNombre.value =
                opcion.dataset.categoria || '';

            categoriaId.value =
                opcion.dataset.categoriaId || '';

        });

    });
</script>

<script>
    const residencia =
        document.getElementById('residencia_id');

    const habitaciones =
        document.querySelector('[name="habitacion_id"]');

    residencia.addEventListener('change', function() {

        const residenciaSeleccionada = this.value;

        Array.from(habitaciones.options).forEach(opcion => {

            if (opcion.value === '') {
                opcion.hidden = false;
                return;
            }

            opcion.hidden =
                opcion.dataset.residencia !== residenciaSeleccionada;
        });

        habitaciones.value = '';
    });
</script>

@endsection