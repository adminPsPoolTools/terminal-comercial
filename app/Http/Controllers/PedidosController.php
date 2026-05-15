<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial = session('comercial_id');
        $estados   = $this->api->obtenerEstadosPedido();

        return view('pedidos.index', compact('comercial', 'estados'));
    }

    public function detalle(string $codigo)
    {
        $cabecera = $this->api->obtenerDetallePedido($codigo);
        $lineas   = $this->api->obtenerLineasPedido($codigo);
        return view('pedidos.detalle', compact('cabecera', 'lineas', 'codigo'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'cliente'         => $request->input('cliente', ''),
            'fecha_desde'     => $request->input('fecha_desde', date('01/01/Y')),
            'titulo'          => $request->input('titulo', ''),
            'estado'          => $request->input('estado', ''),
            'comercial'       => $comercial,
            'estado_servido'  => $request->input('estado_servido', ''),
        ];

        $pedidos     = $this->api->buscarPedidos($filtros);
        $hideCliente = !empty($filtros['cliente']);
        return view('pedidos.list', compact('pedidos', 'comercial', 'hideCliente'));
    }
}
