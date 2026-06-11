@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">✏ Editar Pago</h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('pagos.update', $pago->id) }}" method="POST">

            @csrf
            @method('PUT')

            <!-- REGISTRO -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Registro
                </label>

                <select
                    name="registro_residencia_id"
                    class="w-full border rounded-lg px-4 py-2">

                    @foreach($registros as $registro)

                    <option
                        value="{{ $registro->id }}"
                        {{ old('registro_residencia_id', $pago->registro_residencia_id) == $registro->id ? 'selected' : '' }}>

                        {{ $registro->user->name }}
                        - Habitación {{ $registro->habitacion->numero }}

                    </option>

                    @endforeach

                </select>

            </div>

            <!-- MONTO -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Monto (Bs)
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="monto"
                    value="{{ old('monto', $pago->monto) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- FECHA -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Fecha de Pago
                </label>

                <input
                    type="date"
                    name="fecha_pago"
                    value="{{ old('fecha_pago', $pago->fecha_pago) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- MÉTODO -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Método de Pago
                </label>

                <select
                    name="metodo_pago"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="Efectivo"
                        {{ old('metodo_pago', $pago->metodo_pago) == 'Efectivo' ? 'selected' : '' }}>
                        Efectivo
                    </option>

                    <option value="Transferencia"
                        {{ old('metodo_pago', $pago->metodo_pago) == 'Transferencia' ? 'selected' : '' }}>
                        Transferencia
                    </option>

                    <option value="QR"
                        {{ old('metodo_pago', $pago->metodo_pago) == 'QR' ? 'selected' : '' }}>
                        QR
                    </option>

                </select>

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('pagos.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                    Actualizar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection