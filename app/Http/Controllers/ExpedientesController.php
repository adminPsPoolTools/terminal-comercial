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
            'fecha_desde'     => $this->fechaApi($request->input('fecha_desde'), '01/01/2000'),
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
}
