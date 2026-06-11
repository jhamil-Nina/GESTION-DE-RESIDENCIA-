<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\RegistroResidencia;
use Illuminate\Http\Request;

class PagoController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $registros = RegistroResidencia::with([
            'user',
            'habitacion',
            'pagos'
        ])

            ->when($buscar, function ($query, $buscar) {

                $query->whereHas('user', function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%");
                })

                    ->orWhereHas('habitacion', function ($q) use ($buscar) {
                        $q->where('numero', 'like', "%{$buscar}%");
                    });
            })

            ->get();

        // Estadísticas
        $totalRecaudado = 0;
        $totalDeuda = 0;
        $residentesConDeuda = 0;

        foreach ($registros as $registro) {

            $costo = $registro->habitacion->costo_mensual;

            $pagado = $registro->pagos->sum('monto');

            $deuda = max(0, $costo - $pagado);

            $totalRecaudado += $pagado;
            $totalDeuda += $deuda;

            if ($deuda > 0) {
                $residentesConDeuda++;
            }
        }

        return view('pagos.index', compact(
            'registros',
            'buscar',
            'totalRecaudado',
            'totalDeuda',
            'residentesConDeuda'
        ));
    }


    // FORM CREAR
    public function create()
    {
        $registros = RegistroResidencia::with(
            'user',
            'habitacion',
            'pagos'
        )
            ->get()
            ->filter(function ($registro) {

                $costo = $registro->habitacion->costo_mensual;

                $pagado = $registro->pagos->sum('monto');

                return $pagado < $costo;
            });

        return view('pagos.create', compact('registros'));
    }


    // GUARDAR
    public function store(Request $request)
    {
    $request->validate([
        'registro_residencia_id' => 'required|exists:registro_residencias,id',
        'monto' => 'required|numeric|min:0',
        'fecha_pago' => 'required|date',
        'metodo_pago' => 'required|string|max:50'
    ]);

    $registro = RegistroResidencia::with(
        'habitacion',
        'pagos'
    )->findOrFail(
        $request->registro_residencia_id
    );

    $costo = $registro->habitacion->costo_mensual;

    $pagado = $registro->pagos->sum('monto');

    $deuda = $costo - $pagado;

    // Evitar sobrepago
    if ($request->monto > $deuda) {

        return back()
            ->withInput()
            ->with(
                'error',
                '⚠ Sobrepago detectado. Solo puede cancelar Bs ' . number_format($deuda, 2)
            );
    }

    $totalPagado = $pagado + $request->monto;

    $estado = $totalPagado >= $costo
        ? 'Pagado'
        : 'Pendiente';

    Pago::create([

        'registro_residencia_id' =>
        $request->registro_residencia_id,

        'monto' =>
        $request->monto,

        'fecha_pago' =>
        $request->fecha_pago,

        'metodo_pago' =>
        $request->metodo_pago,

        'estado' =>
        $estado,
    ]);

    return redirect()
        ->route('pagos.index')
        ->with(
            'success',
            'Pago registrado correctamente'
        );
    }


    // MOSTRAR
    public function show(string $id)
    {
        $pago = Pago::with(
            'registroResidencia.user',
            'registroResidencia.habitacion'
        )->findOrFail($id);

        $historialPagos = Pago::where(
            'registro_residencia_id',
            $pago->registro_residencia_id
        )
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return view(
            'pagos.show',
            compact('pago', 'historialPagos')
        );
    }

    // FORM EDITAR
    public function edit(string $id)
    {
        $pago = Pago::findOrFail($id);

        $registros = RegistroResidencia::with('user', 'habitacion')->get();

        return view('pagos.edit', compact('pago', 'registros'));
    }


    // ACTUALIZAR
    public function update(Request $request, string $id)
    {
        $request->validate([
            'registro_residencia_id' => 'required|exists:registro_residencias,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string|max:50'
        ]);

        $pago = Pago::findOrFail($id);

        $registro = RegistroResidencia::with(
            'habitacion',
            'pagos'
        )->findOrFail(
            $request->registro_residencia_id
        );

        $costo = $registro->habitacion->costo_mensual;

        // Suma de pagos excepto el actual
        $pagado = Pago::where(
            'registro_residencia_id',
            $request->registro_residencia_id
        )
            ->where('id', '!=', $pago->id)
            ->sum('monto');

        $deuda = $costo - $pagado;

        // Validar sobrepago
        if ($request->monto > $deuda) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    '⚠ Sobrepago detectado. Solo puede cancelar Bs ' . number_format($deuda, 2)
                );
        }

        $totalPagado = $pagado + $request->monto;

        $estado = $totalPagado >= $costo
            ? 'Pagado'
            : 'Pendiente';

        $pago->update([

            'registro_residencia_id' =>
            $request->registro_residencia_id,

            'monto' =>
            $request->monto,

            'fecha_pago' =>
            $request->fecha_pago,

            'metodo_pago' =>
            $request->metodo_pago,

            'estado' =>
            $estado,
        ]);

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago actualizado correctamente'
            );
    }


    // ELIMINAR
    public function destroy(string $id)
    {
        $pago = Pago::findOrFail($id);

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente');
    }
} 
