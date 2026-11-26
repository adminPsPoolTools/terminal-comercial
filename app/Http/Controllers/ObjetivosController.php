<?php
namespace App\Http\Controllers;
use App\Services\ApiService; use Illuminate\Http\Request;

class ObjetivosController extends Controller {
    public function __construct(protected ApiService $api) {}

    public function index(Request $request) {
        $comercial = (int) session('comercial_id');
        $anio      = (int) $request->input('anio', date('Y'));
        if ($anio < 2000 || $anio > 2100) {
            $anio = (int) date('Y');
        }

        // La API ejecuta RELLENAR_TMPLSTOBJETIVOSMESES(fecha_desde, fecha_hasta, comercial)
        // y devuelve los objetivos cuyo periodo (objetivos_comerciales) cubre el rango.
        $res = $this->api->listadoObjetivos([
            'fecha_desde' => $this->fechaApi("01/01/{$anio}"),
            'fecha_hasta' => $this->fechaApi("31/12/{$anio}"),
            'comercial'   => $comercial,
        ]);

        $error        = $res === null;
        $resumen      = (array) ($res->extra->info ?? []);
        $importeExtra = $res->extra->importeTotal ?? null;

        // Detalle mensual agrupado por objetivo
        $mensual = [];
        foreach ((array) ($res->mensual ?? []) as $fila) {
            $mensual[trim((string) ($fila->OBJETIVO_DESCRIPCION ?? '—'))][] = $fila;
        }

        return view('objetivos.index', compact('comercial', 'anio', 'resumen', 'importeExtra', 'mensual', 'error'));
    }
}
