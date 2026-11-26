<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ExpedientesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial  = session('comercial_id');
        $estados    = $this->api->obtenerEstadosExpediente();
        $provincias = $this->api->obtenerProvincias();
        $vendedores = $this->api->obtenerVendedores();

        return view('expedientes.index', compact('comercial', 'estados', 'provincias', 'vendedores'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'cliente'         => '',
            'fecha_desde'     => $this->fechaApi($request->input('fecha_desde'), date('Y-m-d', strtotime('-1 month'))), // por defecto, último mes
            // La API compara UPPER(descripcion) LIKE '%texto%' sin pasar el texto a mayúsculas
            'descripcion'     => mb_strtoupper(trim((string) $request->input('descripcion', ''))),
            'comercial'       => $comercial,
            'provincia'       => $request->input('provincia', ''),
            'poblacion'       => $request->input('poblacion', ''),
            'estado'          => $request->input('estado', '0'),
            'com_asig'        => $request->input('com_asig', '0'),
            'cliente_presu'   => $request->input('cliente_presu', ''),
            'cliente_asign'   => $request->input('cliente_asign', ''),
        ];

        $expedientes = $this->api->buscarExpedientes($filtros);

        // La API devuelve solo el código del comercial: se traduce a nombre
        $vendedores = [];
        if (! empty($expedientes)) {
            foreach ($this->api->obtenerVendedores() as $v) {
                $vendedores[trim((string) ($v->CODIGO ?? ''))] = $v->NOMBRE ?? '';
            }
        }

        return view('expedientes.list', compact('expedientes', 'comercial', 'vendedores'));
    }

    /**
     * Informe del expediente: datos, clientes, contactos, agendas, presupuestos, pedidos y visitas.
     * Sustituye a informe_expediente.php del sistema antiguo.
     */
    public function detalle(string $codigo)
    {
        $expediente = $this->api->obtenerExpediente($codigo);

        if (! $expediente) {
            abort(404, 'Expediente no encontrado');
        }

        $estadoDesc = '';
        foreach ($this->api->obtenerEstadosExpediente() as $e) {
            if (trim((string) ($e->CODIGO ?? '')) === trim((string) ($expediente->ESTADO ?? ''))) {
                $estadoDesc = $e->DESCRIPCION ?? '';
                break;
            }
        }

        $comercialNombre = '';
        foreach ($this->api->obtenerVendedores() as $v) {
            if (trim((string) ($v->CODIGO ?? '')) === trim((string) ($expediente->COMERCIAL ?? ''))) {
                $comercialNombre = $v->NOMBRE ?? '';
                break;
            }
        }

        $clientes     = $this->api->obtenerClientesExpediente($codigo);
        $contactos    = $this->api->obtenerContactosExpediente($codigo);
        $agendas      = $this->api->obtenerAgendasExpediente($codigo);
        $presupuestos = $this->api->obtenerPresupuestosExpediente($codigo);
        $pedidos      = $this->api->obtenerPedidosExpediente($codigo);
        $visitas      = $this->api->obtenerVisitasExpediente($codigo);

        return view('expedientes.detalle', compact(
            'expediente', 'codigo', 'estadoDesc', 'comercialNombre',
            'clientes', 'contactos', 'agendas', 'presupuestos', 'pedidos', 'visitas'
        ));
    }
}
