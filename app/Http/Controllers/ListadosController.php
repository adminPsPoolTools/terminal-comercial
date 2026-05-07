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
        $filtros   = array_merge(['comercial' => $comercial], $request->only(['fecha_desde','fecha_hasta','tipo']));
        $datos     = $this->api->listadoVentasClientes($filtros);
        return view('listados.ventas_clientes', compact('datos', 'comercial'));
    }

    public function clientes(Request $request)
    {
        $comercial = session('comercial_id');
        $filtros   = array_merge(['comercial' => $comercial], $request->only(['fecha_desde','fecha_hasta','nombre_cliente','provincia','poblacion','categoria_cliente']));
        $clientes  = $this->api->listadoClientes($filtros);
        return view('listados.clientes', compact('clientes', 'comercial'));
    }
}
