<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Mensualidad;
use App\Models\RegistroResidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Residencia;

class PagoController extends Controller
{
    // LISTA DE PAGOS Y DEUDAS
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $registros = RegistroResidencia::with([
            'user',
            'habitacion',
            'pagos',
            'mensualidades'
        ])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->whereHas('user', function ($sub) use ($buscar) {
                        $sub->where('name', 'like', "%{$buscar}%");
                    })
                    ->orWhereHas('habitacion', function ($sub) use ($buscar) {
                        $sub->where('numero', 'like', "%{$buscar}%");
                    });
                });
            })
            ->latest()
            ->get();

        $totalRecaudado = Pago::where('estado', 'Pagado')
            ->sum('monto');

        $totalDeuda = Mensualidad::where('estado', 'Pendiente')
            ->sum('monto');

        $residentesConDeuda = RegistroResidencia::whereHas(
            'mensualidades',
            function ($query) {
                $query->where('estado', 'Pendiente');
            }
        )
            ->distinct()
            ->count('user_id');

        return view('pagos.index', compact(
            'registros',
            'buscar',
            'totalRecaudado',
            'totalDeuda',
            'residentesConDeuda'
        ));
    }

    // FORMULARIO PARA REGISTRAR UN PAGO
    public function create()
    {
        $residencias = Residencia::orderBy('nombre')->get();

        $registros = RegistroResidencia::with([
            'user',
            'habitacion.residencia',
            'mensualidades' => function ($query) {
                $query->where('estado', 'Pendiente')
                    ->orderBy('numero_mes');
            }
        ])
            ->whereHas('mensualidades', function ($query) {
                $query->where('estado', 'Pendiente');
            })
            ->latest()
            ->get();

        return view('pagos.create', compact(
            'residencias',
            'registros'
        ));
    }

    // GUARDAR PAGO DE UNA O VARIAS MENSUALIDADES
    public function store(Request $request)
    {
        $datos = $request->validate([
            'registro_residencia_id' =>
                'required|exists:registro_residencias,id',

            'mensualidades' =>
                'required|array|min:1',

            'mensualidades.*' =>
                'required|integer|distinct|exists:mensualidades,id',

            'fecha_pago' =>
                'required|date',

            'metodo_pago' =>
                'required|string|in:Efectivo,Transferencia,QR',
        ]);

        DB::transaction(function () use ($datos) {

            // Bloquear el contrato para coordinar operaciones
            $registro = RegistroResidencia::whereKey(
                $datos['registro_residencia_id']
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Bloquear las mensualidades seleccionadas
            $mensualidades = Mensualidad::where(
                'registro_residencia_id',
                $registro->id
            )
                ->whereIn('id', $datos['mensualidades'])
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            // Verificar que todas pertenezcan al contrato
            if (
                $mensualidades->count() !==
                count($datos['mensualidades'])
            ) {
                throw ValidationException::withMessages([
                    'mensualidades' =>
                        'Una o varias mensualidades no pertenecen al contrato seleccionado.'
                ]);
            }

            // Evitar cobrar mensualidades ya pagadas
            if ($mensualidades->contains(
                fn ($mensualidad) => $mensualidad->estado !== 'Pendiente'
            )) {
                throw ValidationException::withMessages([
                    'mensualidades' =>
                        'Una o varias mensualidades ya fueron pagadas.'
                ]);
            }

            // Calcular el monto en el servidor
            $montoTotal = round(
                $mensualidades->sum('monto'),
                2
            );

            if ($montoTotal <= 0) {
                throw ValidationException::withMessages([
                    'mensualidades' =>
                        'El monto total debe ser mayor a cero.'
                ]);
            }

            // Crear el pago
            $pago = Pago::create([
                'registro_residencia_id' => $registro->id,
                'monto' => $montoTotal,
                'fecha_pago' => $datos['fecha_pago'],
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => 'Pagado',
            ]);

            // Relacionar cada mensualidad con el pago
            foreach ($mensualidades as $mensualidad) {

                $pago->mensualidades()->attach(
                    $mensualidad->id,
                    [
                        'monto_aplicado' => $mensualidad->monto
                    ]
                );

                $mensualidad->update([
                    'estado' => 'Pagado'
                ]);
            }

        }, 3);

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago registrado correctamente. Las mensualidades seleccionadas fueron canceladas.'
            );
    }

    // VER DETALLE DE UN PAGO
    public function show(string $id)
    {
        $pago = Pago::with([
            'registroResidencia.user',
            'registroResidencia.habitacion',
            'mensualidades'
        ])->findOrFail($id);

        $historialPagos = Pago::where(
            'registro_residencia_id',
            $pago->registro_residencia_id
        )
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return view('pagos.show', compact(
            'pago',
            'historialPagos'
        ));
    }

    // PROTEGER EDICIÓN DE PAGOS REGISTRADOS
    public function edit(string $id)
    {
        Pago::findOrFail($id);

        return redirect()
            ->route('pagos.index')
            ->with(
                'error',
                'Los pagos registrados no pueden editarse directamente.'
            );
    }

    // PROTEGER ACTUALIZACIÓN DE PAGOS
    public function update(Request $request, string $id)
    {
        Pago::findOrFail($id);

        return redirect()
            ->route('pagos.index')
            ->with(
                'error',
                'No se permite modificar pagos registrados. Se requiere un procedimiento de anulación.'
            );
    }

    // PROTEGER ELIMINACIÓN DE PAGOS
    public function destroy(string $id)
    {
        Pago::findOrFail($id);

        return redirect()
            ->route('pagos.index')
            ->with(
                'error',
                'No se permite eliminar pagos registrados para proteger el historial financiero.'
            );
    }
}
