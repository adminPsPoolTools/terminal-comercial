<?php namespace App\Http\Controllers;
use App\Services\ApiService; use Illuminate\Http\Request;
class IncidenciasController extends Controller {
    public function __construct(protected ApiService $api) {}
    public function index() {
        $comercial = session('comercial_id');
        $estados   = $this->api->obtenerEstadosIncidencia();
        return view('incidencias.index', compact('comercial', 'estados'));
    }
    public function list(Request $request) {
        $comercial = session('comercial_id');
        $filtros = [
            'comercial'   => $comercial,
            'fecha_desde' => $this->fechaApi($request->input('fecha_desde'), date('Y-m-d', strtotime('-1 month'))), // por defecto, último mes
            // La API añade "ESTADO LIKE :estado" salvo que reciba exactamente "Todos"
            'estado'      => $request->filled('estado') ? $request->input('estado') : 'Todos',
        ];
        $incidencias = $this->api->buscarIncidenciasSat($filtros);
        return view('incidencias.list', compact('incidencias', 'comercial'));
    }
}
