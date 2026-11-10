<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroResidencia;
use App\Models\User;
use App\Models\Habitacion;
use App\Models\CategoriaOcupacion;
use App\Models\Antecedente;
use App\Models\Residencia;
use App\Models\Mensualidad;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class RegistroResidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $registro_residencias = RegistroResidencia::with([
            'user',
            'habitacion',
            'categoriaOcupacion'
        ])
            ->when($buscar, function ($query, $buscar) {
                $query->whereHas('user', function ($q) use ($buscar) {
                    $q->where('name', 'like', "%$buscar%");
                });
            })
            ->latest()
            ->get();

        return view('registro_residencias.index', compact(
            'registro_residencias',
            'buscar'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::with('categoriaOcupacion')->get();

        $residencias = Residencia::all();

        $habitacions = Habitacion::with('residencia')
            ->where('estado', '!=', 'Mantenimiento')
            ->get();

        return view('registro_residencias.create', compact(
            'users',
            'residencias',
            'habitacions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $datos = $request->validate([
            'user_id' => 'required|exists:users,id',
            'habitacion_id' => 'required|exists:habitacions,id',
            'categoria_ocupacion_id' => 'required|exists:categoria_ocupacions,id',
            'fecha_ingreso' => 'required|date',
            'cantidad_meses' => 'required|integer|min:1|max:120',
        ]);

        // Verificar antecedentes
        $tieneAntecedente = Antecedente::where(
            'user_id',
            $datos['user_id']
        )->exists();

        if ($tieneAntecedente) {
            return back()
                ->withInput()
                ->with('error', 'Este residente tiene antecedentes y no puede registrarse.');
        }

        // Calcular el período contratado
        $fechaIngreso = Carbon::parse($datos['fecha_ingreso'])->startOfDay();

        $cantidadMeses = (int) $datos['cantidad_meses'];

        $fechaSalida = $fechaIngreso->copy()
            ->addMonthsNoOverflow($cantidadMeses);

        DB::transaction(function () use ($datos, $fechaIngreso, $fechaSalida) {

            // Bloquear la habitación durante la validación y el registro
            $habitacion = Habitacion::whereKey($datos['habitacion_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($habitacion->estado === 'Mantenimiento') {
                throw ValidationException::withMessages([
                    'habitacion_id' => 'Esta habitación se encuentra en mantenimiento.'
                ]);
            }

            if ($habitacion->capacidad < 1) {
                throw ValidationException::withMessages([
                    'habitacion_id' => 'La habitación no tiene una capacidad válida.'
                ]);
            }

            // Evitar registros simultáneos del mismo residente
            $registroExistente = RegistroResidencia::where(
                'user_id',
                $datos['user_id']
            )
                ->whereDate('fecha_ingreso', '<', $fechaSalida->toDateString())
                ->where(function ($query) use ($fechaIngreso) {
                    $query->whereNull('fecha_salida')
                        ->orWhereDate(
                            'fecha_salida',
                            '>',
                            $fechaIngreso->toDateString()
                        );
                })
                ->exists();

            if ($registroExistente) {
                throw ValidationException::withMessages([
                    'user_id' => 'El residente ya tiene un registro que coincide con este período.'
                ]);
            }

            // Buscar registros que coincidan con el período solicitado
            $registros = RegistroResidencia::where(
                'habitacion_id',
                $habitacion->id
            )
                ->whereDate('fecha_ingreso', '<', $fechaSalida->toDateString())
                ->where(function ($query) use ($fechaIngreso) {
                    $query->whereNull('fecha_salida')
                        ->orWhereDate(
                            'fecha_salida',
                            '>',
                            $fechaIngreso->toDateString()
                        );
                })
                ->get(['fecha_ingreso', 'fecha_salida']);

            // Comprobar el máximo de ocupantes en todo el período
            $eventos = [];

            foreach ($registros as $registro) {
                $inicio = max(
                    $registro->fecha_ingreso,
                    $fechaIngreso->toDateString()
                );

                $fin = $registro->fecha_salida
                    ? min($registro->fecha_salida, $fechaSalida->toDateString())
                    : $fechaSalida->toDateString();

                $eventos[] = ['fecha' => $inicio, 'cambio' => 1];
                $eventos[] = ['fecha' => $fin, 'cambio' => -1];
            }

            // En una misma fecha, procesar salidas antes que ingresos
            usort($eventos, function ($a, $b) {
                return strcmp($a['fecha'], $b['fecha'])
                    ?: ($a['cambio'] <=> $b['cambio']);
            });

            $ocupantes = 0;

            foreach ($eventos as $evento) {
                $ocupantes += $evento['cambio'];

                if ($ocupantes >= $habitacion->capacidad) {
                    throw ValidationException::withMessages([
                        'habitacion_id' => 'La habitación no tiene capacidad suficiente durante todo el período solicitado.'
                    ]);
                }
            }

            // Obtener el precio desde la base de datos
            $precioMensual = $habitacion->costo_mensual;

            if ($precioMensual <= 0) {
                throw ValidationException::withMessages([
                    'habitacion_id' => 'La habitación debe tener un costo mensual mayor a cero.'
                ]);
            }

            $montoTotal = round(
                $precioMensual * $datos['cantidad_meses'],
                2
            );

            // Crear registro de residencia
            $registro = RegistroResidencia::create([
                'user_id' => $datos['user_id'],
                'habitacion_id' => $habitacion->id,
                'categoria_ocupacion_id' => $datos['categoria_ocupacion_id'],
                'fecha_ingreso' => $fechaIngreso->toDateString(),
                'fecha_salida' => $fechaSalida->toDateString(),
                'cantidad_meses' => $datos['cantidad_meses'],
                'precio_mensual' => $precioMensual,
                'monto_total' => $montoTotal,
            ]);

            // Generar una mensualidad por cada mes contratado
            for ($mes = 0; $mes < $datos['cantidad_meses']; $mes++) {

                $inicioMes = $fechaIngreso->copy()
                    ->addMonthsNoOverflow($mes);

                $finMes = $fechaIngreso->copy()
                    ->addMonthsNoOverflow($mes + 1);

                Mensualidad::create([
                    'registro_residencia_id' => $registro->id,
                    'numero_mes' => $mes + 1,
                    'fecha_inicio' => $inicioMes->toDateString(),
                    'fecha_fin' => $finMes->toDateString(),
                    'monto' => $precioMensual,
                    'estado' => 'Pendiente',
                ]);
            }

            // Actualizar la ocupación actual
            $this->actualizarEstadoHabitacion($habitacion->id);

        }, 3);

        return redirect()
            ->route('registro_residencias.index')
            ->with(
                'success',
                'Registro creado correctamente. Se generaron las mensualidades pendientes.'
            );
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registro = RegistroResidencia::with([
            'user',
            'habitacion',
            'categoriaOcupacion'
        ])->findOrFail($id);

        return view('registro_residencias.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $registro = RegistroResidencia::with('mensualidades')->findOrFail($id);

        $users = User::all();
        $habitacions = Habitacion::with('residencia')->get();
        $categorias = CategoriaOcupacion::all();

        $tienePagos = $registro->mensualidades()
            ->where('estado', 'Pagado')
            ->exists();

        return view('registro_residencias.edit', compact(
            'registro',
            'users',
            'habitacions',
            'categorias',
            'tienePagos'
        ));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $registro = RegistroResidencia::findOrFail($id);

        // Bloquear cualquier modificación de contratos con pagos
        if (
            $registro->pagos()->exists() ||
            $registro->mensualidades()->where('estado', 'Pagado')->exists()
        ) {
            return back()->with(
                'error',
                'Este contrato tiene pagos registrados y no puede modificarse.'
            );
        }

        $datos = $request->validate([
            'fecha_ingreso' => 'required|date',
            'cantidad_meses' => 'required|integer|min:1|max:120',
        ]);

        $fechaIngreso = Carbon::parse($datos['fecha_ingreso'])->startOfDay();

        $cantidadMeses = (int) $datos['cantidad_meses'];

        $fechaSalida = $fechaIngreso->copy()
            ->addMonthsNoOverflow($cantidadMeses);

        DB::transaction(function () use ($registro, $datos, $fechaIngreso, $fechaSalida) {

            // Bloquear habitación para evitar registros simultáneos
            $habitacion = Habitacion::whereKey($registro->habitacion_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Volver a comprobar pagos dentro de la transacción
            if (
                $registro->pagos()->exists() ||
                $registro->mensualidades()->where('estado', 'Pagado')->exists()
            ) {
                throw ValidationException::withMessages([
                    'cantidad_meses' => 'El contrato ya tiene pagos registrados.'
                ]);
            }

            // Evitar fechas superpuestas del mismo residente
            $registroExistente = RegistroResidencia::where('user_id', $registro->user_id)
                ->where('id', '!=', $registro->id)
                ->whereDate('fecha_ingreso', '<', $fechaSalida->toDateString())
                ->where(function ($query) use ($fechaIngreso) {
                    $query->whereNull('fecha_salida')
                        ->orWhereDate('fecha_salida', '>', $fechaIngreso->toDateString());
                })
                ->exists();

            if ($registroExistente) {
                throw ValidationException::withMessages([
                    'fecha_ingreso' => 'El residente ya tiene otro contrato durante este período.'
                ]);
            }

            // Consultar otros residentes de la habitación
            $registros = RegistroResidencia::where('habitacion_id', $habitacion->id)
                ->where('id', '!=', $registro->id)
                ->whereDate('fecha_ingreso', '<', $fechaSalida->toDateString())
                ->where(function ($query) use ($fechaIngreso) {
                    $query->whereNull('fecha_salida')
                        ->orWhereDate('fecha_salida', '>', $fechaIngreso->toDateString());
                })
                ->get(['fecha_ingreso', 'fecha_salida']);

            // Validar capacidad durante todo el contrato
            $eventos = [];

            foreach ($registros as $otroRegistro) {
                $inicio = max(
                    $otroRegistro->fecha_ingreso,
                    $fechaIngreso->toDateString()
                );

                $fin = $otroRegistro->fecha_salida
                    ? min($otroRegistro->fecha_salida, $fechaSalida->toDateString())
                    : $fechaSalida->toDateString();

                $eventos[] = ['fecha' => $inicio, 'cambio' => 1];
                $eventos[] = ['fecha' => $fin, 'cambio' => -1];
            }

            usort($eventos, function ($a, $b) {
                return strcmp($a['fecha'], $b['fecha'])
                    ?: ($a['cambio'] <=> $b['cambio']);
            });

            $ocupantes = 0;

            foreach ($eventos as $evento) {
                $ocupantes += $evento['cambio'];

                if ($ocupantes >= $habitacion->capacidad) {
                    throw ValidationException::withMessages([
                        'fecha_ingreso' => 'La habitación no tiene capacidad suficiente durante el período solicitado.'
                    ]);
                }
            }

            // Conservar el precio original del contrato
            $precioMensual = $registro->precio_mensual;

            if ($precioMensual === null || $precioMensual <= 0) {
                throw ValidationException::withMessages([
                    'cantidad_meses' => 'Este contrato no tiene un precio mensual válido.'
                ]);
            }

            $montoTotal = round(
                $precioMensual * $datos['cantidad_meses'],
                2
            );

            // Actualizar contrato
            $registro->update([
                'fecha_ingreso' => $fechaIngreso->toDateString(),
                'fecha_salida' => $fechaSalida->toDateString(),
                'cantidad_meses' => $datos['cantidad_meses'],
                'monto_total' => $montoTotal,
            ]);

            // Regenerar mensualidades pendientes
            $registro->mensualidades()->delete();

            for ($mes = 0; $mes < $datos['cantidad_meses']; $mes++) {

                $inicioMes = $fechaIngreso->copy()
                    ->addMonthsNoOverflow($mes);

                $finMes = $fechaIngreso->copy()
                    ->addMonthsNoOverflow($mes + 1);

                Mensualidad::create([
                    'registro_residencia_id' => $registro->id,
                    'numero_mes' => $mes + 1,
                    'fecha_inicio' => $inicioMes->toDateString(),
                    'fecha_fin' => $finMes->toDateString(),
                    'monto' => $precioMensual,
                    'estado' => 'Pendiente',
                ]);
            }

            $this->actualizarEstadoHabitacion($habitacion->id);

        }, 3);

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Contrato actualizado y mensualidades regeneradas correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {

            $registro = RegistroResidencia::findOrFail($id);

            // Bloquear la habitación durante la operación
            $habitacion = Habitacion::whereKey($registro->habitacion_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Volver a consultar el registro dentro del bloqueo
            $registro = RegistroResidencia::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Impedir eliminar contratos con pagos
            $tienePagos = $registro->pagos()->exists();

            $tieneMensualidadesPagadas = $registro->mensualidades()
                ->where('estado', 'Pagado')
                ->exists();

            if ($tienePagos || $tieneMensualidadesPagadas) {
                throw ValidationException::withMessages([
                    'registro' => 'No se puede eliminar este contrato porque tiene pagos registrados.'
                ]);
            }

            // Eliminar mensualidades pendientes
            $registro->mensualidades()->delete();

            // Eliminar contrato
            $registro->delete();

            // Actualizar disponibilidad de la habitación
            $this->actualizarEstadoHabitacion($habitacion->id);

        }, 3);

        return redirect()
            ->route('registro_residencias.index')
            ->with('success', 'Registro de residencia eliminado correctamente.');
    }


    private function actualizarEstadoHabitacion(int|string $habitacionId)
    {
        $habitacion = Habitacion::find($habitacionId);

        if (!$habitacion) {
            return;
        }

        $ocupantesActuales = RegistroResidencia::where(
            'habitacion_id',
            $habitacion->id
        )
            ->where(function ($query) {
                $query->whereNull('fecha_salida')
                    ->orWhere('fecha_salida', '>=', now()->toDateString());
            })
            ->count();

        $habitacion->estado = $ocupantesActuales >= $habitacion->capacidad
            ? 'Ocupada'
            : 'Disponible';

        $habitacion->save();
    }
}
