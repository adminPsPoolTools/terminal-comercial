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

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'cliente'         => '',
            'fecha_desde'     => $request->input('fecha_desde', date('01/01/Y')),
            'titulo'          => $request->input('titulo', ''),
            'estado'          => $request->input('estado', ''),
            'comercial'       => $comercial,
            'estado_servido'  => $request->input('estado_servido', ''),
        ];

        $pedidos = $this->api->buscarPedidos($filtros);
        return view('pedidos.list', compact('pedidos', 'comercial'));
    }
}
