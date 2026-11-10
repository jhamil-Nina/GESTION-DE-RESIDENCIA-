@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4">

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">➕ Registrar Pago</h2>
        <p class="text-sm opacity-90 mt-1">
            Seleccione la residencia, el contrato y las mensualidades
        </p>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-5">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-5">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pagos.store') }}" method="POST" id="formPago">
            @csrf

            {{-- RESIDENCIA --}}
            <div class="mb-5">
                <label for="residencia_id" class="block font-semibold mb-2 text-gray-700">
                    Residencia
                </label>

                <select
                    id="residencia_id"
                    name="residencia_id"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500">

                    <option value="">Seleccione una residencia</option>

                    @foreach($residencias as $residencia)
                        <option value="{{ $residencia->id }}"
                            @selected(old('residencia_id') == $residencia->id)>
                            {{ $residencia->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- CONTRATO --}}
            <div class="mb-5">
                <label for="registro_residencia_id" class="block font-semibold mb-2 text-gray-700">
                    Residente / Contrato
                </label>

                <select
                    name="registro_residencia_id"
                    id="registro_residencia_id"
                    required
                    disabled
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 disabled:bg-gray-100">

                    <option value="">Primero seleccione una residencia</option>

                    @foreach($registros as $registro)
                        <option
                            value="{{ $registro->id }}"
                            data-residencia="{{ $registro->habitacion->residencia_id }}"
                            @selected(old('registro_residencia_id') == $registro->id)>

                            {{ $registro->user->name }}
                            - Habitación {{ $registro->habitacion->numero }}
                            (Contrato #{{ $registro->id }})

                        </option>
                    @endforeach

                </select>
            </div>

            {{-- MENSUALIDADES --}}
            <div class="mb-5">

                <h3 class="font-semibold mb-3">
                    Mensualidades pendientes
                </h3>

                <div id="mensajeSeleccion"
                     class="bg-gray-50 border rounded-lg p-4 text-gray-500">
                    Seleccione primero una residencia y un contrato.
                </div>

                @foreach($registros as $registro)
                    <div
                        class="grupo-cuotas hidden"
                        data-registro="{{ $registro->id }}">

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-3">
                            <p class="text-sm text-gray-600">
                                Deuda total del contrato
                            </p>

                            <p class="text-xl font-bold text-red-600">
                                Bs {{ number_format($registro->mensualidades->sum('monto'), 2) }}
                            </p>

                            <p class="text-sm text-gray-600">
                                {{ $registro->mensualidades->count() }} cuotas pendientes
                            </p>
                        </div>

                        <div class="space-y-2">
                            @foreach($registro->mensualidades as $mensualidad)
                                <label class="flex items-center justify-between gap-3 border rounded-lg p-3 hover:bg-green-50 cursor-pointer">

                                    <span class="flex items-center gap-3">
                                        <input
                                            type="checkbox"
                                            name="mensualidades[]"
                                            value="{{ $mensualidad->id }}"
                                            data-monto="{{ $mensualidad->monto }}"
                                            @checked(in_array($mensualidad->id, old('mensualidades', [])))
                                            class="cuota-checkbox w-5 h-5 text-green-600 rounded">

                                        <span>
                                            <span class="block font-semibold">
                                                Mes {{ $mensualidad->numero_mes }}
                                            </span>

                                            <span class="block text-xs text-gray-500">
                                                {{ $mensualidad->fecha_inicio->format('d/m/Y') }}
                                                -
                                                {{ $mensualidad->fecha_fin->format('d/m/Y') }}
                                            </span>
                                        </span>
                                    </span>

                                    <span class="font-semibold text-gray-700 whitespace-nowrap">
                                        Bs {{ number_format($mensualidad->monto, 2) }}
                                    </span>

                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- TOTAL --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5">
                <p class="text-gray-600 text-sm">
                    Cuotas seleccionadas:
                    <strong id="cantidadSeleccionada">0</strong>
                </p>

                <p class="text-xl font-bold text-green-700 mt-2">
                    Total a cobrar:
                    Bs <span id="totalCobrar">0.00</span>
                </p>
            </div>

            {{-- FECHA --}}
            <div class="mb-4">
                <label class="block font-semibold mb-2">
                    Fecha de Pago
                </label>

                <input
                    type="date"
                    name="fecha_pago"
                    value="{{ old('fecha_pago', now()->toDateString()) }}"
                    required
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            {{-- MÉTODO --}}
            <div class="mb-6">
                <label class="block font-semibold mb-2">
                    Método de Pago
                </label>

                <select
                    name="metodo_pago"
                    required
                    class="w-full border rounded-lg px-4 py-2">

                    @foreach(['Efectivo', 'Transferencia', 'QR'] as $metodo)
                        <option value="{{ $metodo }}"
                            @selected(old('metodo_pago', 'Efectivo') == $metodo)>
                            {{ $metodo }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('pagos.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Cancelar
                </a>

                <button type="submit"
                        id="btnGuardar"
                        disabled
                        class="bg-green-500 hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-5 py-2 rounded-lg">
                    Registrar Pago
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const residencia = document.getElementById('residencia_id');
    const contrato = document.getElementById('registro_residencia_id');
    const mensaje = document.getElementById('mensajeSeleccion');
    const grupos = document.querySelectorAll('.grupo-cuotas');
    const totalCobrar = document.getElementById('totalCobrar');
    const cantidad = document.getElementById('cantidadSeleccionada');
    const boton = document.getElementById('btnGuardar');

    const opcionesContrato = Array.from(
        contrato.querySelectorAll('option[data-residencia]')
    );

    const contratoAnterior = @json(old('registro_residencia_id'));

    function actualizarTotal() {
        let centavos = 0;
        let seleccionadas = 0;

        document.querySelectorAll(
            '.grupo-cuotas:not(.hidden) .cuota-checkbox:checked'
        ).forEach(checkbox => {
            centavos += Math.round(Number(checkbox.dataset.monto) * 100);
            seleccionadas++;
        });

        totalCobrar.textContent = (centavos / 100).toFixed(2);
        cantidad.textContent = seleccionadas;
        boton.disabled = seleccionadas === 0;
    }

    function mostrarCuotas() {
        const registroId = contrato.value;

        grupos.forEach(grupo => {
            const activo = grupo.dataset.registro === registroId;

            grupo.classList.toggle('hidden', !activo);

            grupo.querySelectorAll('.cuota-checkbox').forEach(checkbox => {
                checkbox.disabled = !activo;

                if (!activo) {
                    checkbox.checked = false;
                }
            });
        });

        mensaje.classList.toggle('hidden', registroId !== '');
        actualizarTotal();
    }

    function filtrarContratos(valorAnterior = null) {

        const residenciaId = residencia.value;

        contrato.innerHTML = '';

        const opcionInicial = document.createElement('option');
        opcionInicial.value = '';
        opcionInicial.textContent = residenciaId
            ? 'Seleccione un residente / contrato'
            : 'Primero seleccione una residencia';

        contrato.appendChild(opcionInicial);
        contrato.disabled = !residenciaId;

        const filtradas = opcionesContrato.filter(
            opcion => opcion.dataset.residencia === residenciaId
        );

        filtradas.forEach(opcion => {
            contrato.appendChild(opcion);
        });

        if (residenciaId && filtradas.length === 0) {
            opcionInicial.textContent =
                'No hay contratos con mensualidades pendientes';
            contrato.disabled = true;
        }

        if (valorAnterior !== null) {
            contrato.value = String(valorAnterior);
        }

        mostrarCuotas();
    }

    residencia.addEventListener('change', function () {
        filtrarContratos();
    });

    contrato.addEventListener('change', mostrarCuotas);

    document.querySelectorAll('.cuota-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', actualizarTotal);
    });

    filtrarContratos(contratoAnterior);

});
</script>

@endsection

