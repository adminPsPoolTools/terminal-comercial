<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ListadosController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial = session('comercial_id');
        return view('listados.index', compact('comercial'));
    }

    public function ventasClientes(Request $request)
    {
        $comercial = session('comercial_id');
        $filtros   = [
            'comercial'   => $comercial,
            'fecha_desde' => $this->fechaApi($request->input('fecha_desde'), date('01/m/Y')),
            'fecha_hasta' => $this->fechaApi($request->input('fecha_hasta'), date('t/m/Y')),
            'tipo'        => $request->input('tipo', 'mensual'),
        ];
        $datos     = $this->api->listadoVentasClientes($filtros);
        return view('listados.ventas_clientes', compact('datos', 'comercial'));
    }

    public function clientes(Request $request)
    {
        $comercial = session('comercial_id');
        $filtros   = [
            'comercial'            => $comercial,
            'nombre_cliente'       => $request->input('nombre_cliente', ''),
            'categoria_cliente'    => $request->input('categoria_cliente', ''),
            'provincia'            => $request->input('provincia', ''),
            'poblacion'            => $request->input('poblacion', ''),
            // Mismos nombres de filtro que usa ClientesController::list contra
            // el mismo endpoint (obtenerListadoClientes)
            'fecha_desde_clientes' => $this->fechaApi($request->input('fecha_desde')),
            'fecha_alta'           => '',
            'fecha_visitado'       => '',
        ];
        $clientes  = $this->api->listadoClientes($filtros);
        return view('listados.clientes', compact('clientes', 'comercial'));
    }
}
