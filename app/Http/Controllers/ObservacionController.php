<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Observacion;
use App\Models\RegistroResidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Residencia;

class ObservacionController extends Controller
{
    // LISTAR
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $observaciones = Observacion::with('habitacion.residencia')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('descripcion', 'like', "%{$buscar}%")
                      ->orWhereHas('habitacion', function ($h) use ($buscar) {
                          $h->where('numero', 'like', "%{$buscar}%");
                      });

                    if (ctype_digit((string) $buscar)) {
                        $q->orWhere('id', $buscar);
                    }
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('observacions.index', compact('observaciones', 'buscar'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        // Obtener las residencias registradas
        $residencias = Residencia::orderBy('nombre')->get();

        // Obtener habitaciones con su residencia
        $habitaciones = Habitacion::with('residencia')
            ->orderBy('numero')
            ->get();

        return view('observacions.create', compact(
            'residencias',
            'habitaciones'
        ));
    }

    // GUARDAR
    public function store(Request $request)
    {
        $datos = $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'descripcion' => 'required|string'
        ]);

        DB::transaction(function () use ($datos) {

            $fecha = today();

            $observacion = Observacion::create([
                'habitacion_id' => $datos['habitacion_id'],
                'descripcion' => $datos['descripcion'],
                'fecha' => $fecha,
                'estado' => 'Pendiente',
                'fecha_solucion' => null
            ]);

            // Buscar ocupantes activos de la habitación
            $residentes = RegistroResidencia::where(
                'habitacion_id',
                $datos['habitacion_id']
            )
                ->whereDate('fecha_ingreso', '<=', $fecha)
                ->where(function ($query) use ($fecha) {
                    $query->whereNull('fecha_salida')
                          ->orWhereDate('fecha_salida', '>', $fecha);
                })
                ->pluck('id')
                ->toArray();

            // Guardar los ocupantes encontrados
            $observacion->registrosResidencia()->attach($residentes);
        });

        return redirect()->route('observacions.index')
            ->with('success', 'Observación registrada correctamente');
    }

    // MOSTRAR
    public function show(int $id)
    {
        $observacion = Observacion::with([
            'habitacion.residencia',
            'registrosResidencia.user'
        ])->findOrFail($id);

        return view('observacions.show', compact('observacion'));
    }

    // FORMULARIO EDITAR
    public function edit(int $id)
    {
        $observacion = Observacion::findOrFail($id);

        $habitaciones = Habitacion::with('residencia')
            ->orderBy('numero')
            ->get();

        return view('observacions.edit', compact('observacion', 'habitaciones'));
    }

    // ACTUALIZAR
    public function update(Request $request, int $id)
    {
        $datos = $request->validate([
            'habitacion_id' => 'required|exists:habitacions,id',
            'descripcion' => 'required|string',
            'estado' => [
                'required',
                Rule::in([
                    'Pendiente',
                    'En reparación',
                    'Solucionado'
                ])
            ]
        ]);

        $observacion = Observacion::findOrFail($id);

        // Mantener la habitación original para conservar el historial
        if ((int) $datos['habitacion_id'] !== (int) $observacion->habitacion_id) {
            return back()
                ->withErrors([
                    'habitacion_id' => 'No se puede cambiar la habitación de una observación existente.'
                ])
                ->withInput();
        }

        // Registrar automáticamente la fecha de solución
        if ($datos['estado'] === 'Solucionado') {
            if ($observacion->estado !== 'Solucionado'
                || $observacion->fecha_solucion === null) {
                $datos['fecha_solucion'] = now();
            }
        } else {
            $datos['fecha_solucion'] = null;
        }

        $observacion->update($datos);

        return redirect()->route('observacions.index')
            ->with('success', 'Observación actualizada correctamente');
    }

    // ELIMINAR
    public function destroy(int $id)
    {
        $observacion = Observacion::findOrFail($id);

        $observacion->delete();

        return redirect()->route('observacions.index')
            ->with('success', 'Observación eliminada correctamente');
    }
}
