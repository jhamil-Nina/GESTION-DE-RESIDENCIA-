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

                <label class="font-semibold block mb-2">
                    Habitación
                </label>

                <select
                    name="habitacion_id"
                    id="habitacion_id"
                    required
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione una habitación</option>

                    @foreach($habitacions as $habitacion)

                        <option
                            value="{{ $habitacion->id }}"
                            data-residencia="{{ $habitacion->residencia_id }}"
                            data-precio="{{ $habitacion->costo_mensual }}">

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



        {{-- FECHA DE INGRESO --}}
        <div class="mb-4">
            <label class="font-semibold block mb-2">
                Fecha de Ingreso
            </label>

            <input
                type="date"
                name="fecha_ingreso"
                id="fecha_ingreso"
                value="{{ old('fecha_ingreso') }}"
                required
                class="w-full border rounded-lg px-4 py-2">

            @error('fecha_ingreso')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- CANTIDAD DE MESES --}}
        <div class="mb-4">
            <label class="font-semibold block mb-2">
                Cantidad de Meses
            </label>

            <input
                type="number"
                name="cantidad_meses"
                id="cantidad_meses"
                min="1"
                step="1"
                value="{{ old('cantidad_meses', 1) }}"
                required
                class="w-full border rounded-lg px-4 py-2">

            @error('cantidad_meses')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- RESUMEN DEL COSTO --}}
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">

            <h3 class="font-semibold text-green-800 mb-3">
                Resumen del Registro
            </h3>

            <div class="space-y-2 text-sm text-gray-700">

                <p>
                    <strong>Fecha de finalización:</strong>
                    <span id="fecha_fin_calculada">—</span>
                </p>

                <p>
                    <strong>Precio mensual:</strong>
                    Bs <span id="precio_mensual">0.00</span>
                </p>

                <p>
                    <strong>Meses contratados:</strong>
                    <span id="meses_resumen">1</span>
                </p>

                <p class="text-lg font-bold text-green-700">
                    Total a pagar:
                    Bs <span id="monto_total">0.00</span>
                </p>

            </div>

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
document.addEventListener('DOMContentLoaded', function () {

    const residencia = document.getElementById('residencia_id');
    const habitaciones = document.querySelector('[name="habitacion_id"]');

    // Guardar las habitaciones originales
    const opcionesOriginales = Array.from(habitaciones.options)
        .filter(opcion => opcion.value !== '')
        .map(opcion => ({
            value: opcion.value,
            texto: opcion.textContent.trim(),
            residencia: opcion.dataset.residencia,
            precio: opcion.dataset.precio
        }));

    function filtrarHabitaciones() {

        const residenciaSeleccionada = residencia.value;

        // Limpiar las opciones anteriores
        habitaciones.innerHTML = '<option value="">Seleccione una habitación</option>';

        // Agregar únicamente habitaciones de la residencia seleccionada
        opcionesOriginales.forEach(opcion => {

            if (opcion.residencia === residenciaSeleccionada) {

                const nuevaOpcion = document.createElement('option');

                nuevaOpcion.value = opcion.value;
                nuevaOpcion.textContent = opcion.texto;
                nuevaOpcion.dataset.precio = opcion.precio;

                habitaciones.appendChild(nuevaOpcion);
            }

        });

        // Actualizar el resumen del costo
        habitaciones.dispatchEvent(new Event('change'));
    }

    residencia.addEventListener('change', filtrarHabitaciones);

    // Filtrar también al cargar la página
    filtrarHabitaciones();

});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const habitacion = document.querySelector('[name="habitacion_id"]');
    const fechaIngreso = document.getElementById('fecha_ingreso');
    const cantidadMeses = document.getElementById('cantidad_meses');

    const precioMensual = document.getElementById('precio_mensual');
    const mesesResumen = document.getElementById('meses_resumen');
    const montoTotal = document.getElementById('monto_total');
    const fechaFin = document.getElementById('fecha_fin_calculada');

    function calcular() {
        const opcion = habitacion.options[habitacion.selectedIndex];
        const precio = Number(opcion?.dataset.precio || 0);
        const meses = Number(cantidadMeses.value);

        precioMensual.textContent = precio.toFixed(2);
        mesesResumen.textContent = meses > 0 ? meses : '—';

        const mesesValidos = Number.isSafeInteger(meses) && meses >= 1;

        montoTotal.textContent = mesesValidos
            ? (precio * meses).toFixed(2)
            : '0.00';

        fechaFin.textContent = '—';

        if (fechaIngreso.value && mesesValidos) {
            const [anio, mes, dia] = fechaIngreso.value.split('-').map(Number);

            // Avanzar meses y ajustar al último día válido
            const destino = new Date(Date.UTC(anio, mes - 1 + meses, 1));
            const ultimoDia = new Date(Date.UTC(
                destino.getUTCFullYear(),
                destino.getUTCMonth() + 1,
                0
            )).getUTCDate();

            destino.setUTCDate(Math.min(dia, ultimoDia));

            fechaFin.textContent = destino.toLocaleDateString('es-BO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                timeZone: 'UTC'
            });
        }
    }

    habitacion.addEventListener('change', calcular);
    fechaIngreso.addEventListener('change', calcular);
    cantidadMeses.addEventListener('input', calcular);

    calcular();
});
</script>


@endsection
