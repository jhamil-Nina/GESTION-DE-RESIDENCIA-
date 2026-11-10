
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">👁 Detalle de Pago</h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div>
                <p class="text-sm text-gray-500">Número de Pago</p>
                <p class="font-semibold">#{{ $pago->id }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Residente</p>
                <p class="font-semibold">
                    {{ $pago->registroResidencia->user->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Habitación</p>
                <p class="font-semibold">
                    {{ $pago->registroResidencia->habitacion->numero }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Fecha de Pago</p>
                <p class="font-semibold">
                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Método</p>
                <p class="font-semibold">{{ $pago->metodo_pago }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Estado</p>
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                    {{ $pago->estado }}
                </span>
            </div>

        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600">Monto Cobrado</p>
            <p class="text-2xl font-bold text-green-700">
                Bs {{ number_format($pago->monto, 2) }}
            </p>
        </div>

        <h3 class="text-lg font-bold mb-3">
            Mensualidades Canceladas
        </h3>

        <div class="overflow-x-auto mb-6">
            <table class="w-full border rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Mes</th>
                        <th class="px-4 py-3 text-left">Período</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pago->mensualidades as $mensualidad)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                Mes {{ $mensualidad->numero_mes }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $mensualidad->fecha_inicio->format('d/m/Y') }}
                                -
                                {{ $mensualidad->fecha_fin->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                Bs {{ number_format($mensualidad->pivot->monto_aplicado, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">
                                Este pago no tiene mensualidades asociadas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3 class="text-lg font-bold mb-3">
            Historial de Pagos del Contrato
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full border rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                        <th class="px-4 py-3 text-center">Detalle</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($historialPagos as $historial)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($historial->fecha_pago)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                Bs {{ number_format($historial->monto, 2) }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('pagos.show', $historial->id) }}"
                                   class="text-blue-600 hover:underline">
                                    Ver pago #{{ $historial->id }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('pagos.index') }}"
               class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                Volver
            </a>
        </div>

    </div>
</div>

@endsection
