@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nueva Observación
        </h2>

        <p class="text-sm opacity-90 mt-1">
            Registrar daños o desperfectos de una habitación
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        {{-- MENSAJES DE ERROR --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-5">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('observacions.store') }}" method="POST">

            @csrf

            {{-- RESIDENCIA --}}
            <div class="mb-5">

                <label for="residencia_id"
                       class="block font-semibold mb-2 text-gray-700">
                    Residencia
                </label>

                <select
                    id="residencia_id"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

                    <option value="">
                        Seleccione una residencia
                    </option>

                    @foreach($residencias as $residencia)
                        <option
                            value="{{ $residencia->id }}"
                            @selected(old('residencia_id') == $residencia->id)>
                            {{ $residencia->nombre }}
                        </option>
                    @endforeach

                </select>

            </div>

            {{-- HABITACIÓN --}}
            <div class="mb-5">

                <label for="habitacion_id"
                       class="block font-semibold mb-2 text-gray-700">
                    Habitación
                </label>

                <select
                    id="habitacion_id"
                    name="habitacion_id"
                    required
                    disabled
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:bg-gray-100">

                    <option value="">
                        Primero seleccione una residencia
                    </option>

                </select>

                <p class="text-sm text-gray-500 mt-2">
                    Solo se mostrarán las habitaciones de la residencia seleccionada.
                    Los ocupantes actuales se asociarán automáticamente.
                </p>

            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="mb-5">

                <label for="descripcion"
                       class="block font-semibold mb-2 text-gray-700">
                    Descripción del daño
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    required
                    placeholder="Ejemplo: La cortina de la ventana está rota..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('descripcion') }}</textarea>

            </div>

            {{-- INFORMACIÓN AUTOMÁTICA --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">

                <h3 class="font-semibold text-emerald-800 mb-2">
                    Información automática
                </h3>

                <div class="space-y-2 text-sm text-gray-700">

                    <p>
                        <span class="font-semibold">Fecha:</span>
                        Se registrará automáticamente.
                    </p>

                    <p>
                        <span class="font-semibold">Estado inicial:</span>
                        <span class="text-amber-700 font-semibold">
                            Pendiente
                        </span>
                    </p>

                    <p>
                        <span class="font-semibold">Residentes:</span>
                        Se asociarán los ocupantes actuales.
                    </p>

                    <p class="text-gray-500">
                        Si la habitación está vacía, la observación
                        se registrará igualmente.
                    </p>

                </div>

            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('observacions.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg transition">
                    Guardar Observación
                </button>

            </div>

        </form>

    </div>

</div>

{{-- HABITACIONES DISPONIBLES PARA FILTRAR --}}
<script type="application/json" id="datos-habitaciones">
@json($habitaciones->map(fn($habitacion) => [
    'id' => $habitacion->id,
    'numero' => $habitacion->numero,
    'residencia_id' => $habitacion->residencia_id
])->values())
</script>

{{-- FILTRO DEPENDIENTE --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const residencia = document.getElementById('residencia_id');
    const habitacion = document.getElementById('habitacion_id');

    const habitaciones = JSON.parse(
        document.getElementById('datos-habitaciones').textContent
    );

    const habitacionAnterior = @json(old('habitacion_id'));

    function filtrarHabitaciones(valorAnterior = null) {

        const residenciaId = residencia.value;

        habitacion.innerHTML = '';

        const opcionInicial = document.createElement('option');
        opcionInicial.value = '';
        opcionInicial.textContent = residenciaId
            ? 'Seleccione una habitación'
            : 'Primero seleccione una residencia';

        habitacion.appendChild(opcionInicial);

        habitacion.disabled = !residenciaId;

        if (!residenciaId) {
            return;
        }

        const filtradas = habitaciones.filter(
            item => String(item.residencia_id) === residenciaId
        );

        filtradas.forEach(item => {

            const opcion = document.createElement('option');

            opcion.value = item.id;
            opcion.textContent = 'Habitación ' + item.numero;

            habitacion.appendChild(opcion);
        });

        if (filtradas.length === 0) {
            opcionInicial.textContent =
                'Esta residencia no tiene habitaciones registradas';
            habitacion.disabled = true;
        }

        if (valorAnterior !== null) {
            habitacion.value = String(valorAnterior);
        }
    }

    residencia.addEventListener('change', function () {
        filtrarHabitaciones();
    });

    // Recuperar selección después de un error de validación
    filtrarHabitaciones(habitacionAnterior);

});
</script>

@endsection
