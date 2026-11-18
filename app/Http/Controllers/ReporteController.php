<?php

namespace App\Http\Controllers;

use App\Models\Mensualidad;
use App\Models\Observacion;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function __invoke(Request $request, string $tipo = 'danos')
    {
        abort_unless($request->user()->rol === 'admin', 403);

        $filtros = $request->validate([
            'estado' => 'sometimes|in:abiertas,Pendiente,En reparación,Solucionado,todas',
            'formato' => 'sometimes|in:imprimir,pdf',
        ]);

        $estado = $filtros['estado'] ?? 'abiertas';
        $formato = $filtros['formato'] ?? null;
        $fechaCorte = today();

        if ($tipo === 'danos') {
            $titulo = 'Habitaciones dañadas';
            $columnas = ['Residencia', 'Habitación', 'Fecha', 'Estado', 'Descripción'];
            $consultar = Observacion::query()
                ->join('habitacions', 'habitacions.id', '=', 'observacions.habitacion_id')
                ->join('residencias', 'residencias.id', '=', 'habitacions.residencia_id')
                ->select('residencias.nombre as residencia', 'habitacions.numero', 'observacions.fecha', 'observacions.estado', 'observacions.descripcion')
                ->when($estado === 'abiertas', fn ($query) => $query->whereIn('observacions.estado', ['Pendiente', 'En reparación']))
                ->when(!in_array($estado, ['abiertas', 'todas'], true), fn ($query) => $query->where('observacions.estado', $estado))
                ->orderByDesc('observacions.fecha')->orderByDesc('observacions.id');
            $fila = fn ($dato) => [$dato->residencia, $dato->numero, $dato->fecha, $dato->estado, $dato->descripcion];
        } else {
            $titulo = 'Residentes deudores';
            $columnas = ['Residente', 'Correo', 'Cuotas vencidas', 'Vencimiento más antiguo', 'Deuda vencida (Bs)'];
            $consultar = Mensualidad::query()
                ->join('registro_residencias', 'registro_residencias.id', '=', 'mensualidades.registro_residencia_id')
                ->join('users', 'users.id', '=', 'registro_residencias.user_id')
                ->where('mensualidades.estado', 'Pendiente')
                ->where('mensualidades.fecha_fin', '<', $fechaCorte->toDateString())
                ->select('users.name', 'users.email')
                ->selectRaw('COUNT(*) as cuotas, MIN(mensualidades.fecha_fin) as primer_vencimiento, SUM(mensualidades.monto) as deuda')
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('deuda')->orderBy('users.id');
            $fila = fn ($dato) => [$dato->name, $dato->email, $dato->cuotas, $dato->primer_vencimiento, number_format((float) $dato->deuda, 2, ',', '')];
        }

        $datos = compact('tipo', 'titulo', 'columnas', 'fila', 'estado', 'fechaCorte');

        if ($formato === 'pdf') {
            $vista = view('reportes.imprimir', [...$datos, 'descargaPdf' => true, 'registros' => $consultar->cursor()]);
            $pdf = new Dompdf(new Options([
                'defaultMediaType' => 'print',
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'isJavascriptEnabled' => false,
            ]));
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadHtml($vista->render(), 'UTF-8');
            $pdf->render();

            $nombre = "reporte-{$tipo}-{$fechaCorte->toDateString()}.pdf";

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
                'Cache-Control' => 'private, no-store',
            ]);
        }

        if ($formato === 'imprimir') {
            return view('reportes.imprimir', [...$datos, 'registros' => $consultar->cursor()]);
        }

        return view('reportes.index', [...$datos, 'registros' => $consultar->paginate(20)->withQueryString()]);
    }
}
