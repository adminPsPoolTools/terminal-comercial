<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial  = session('comercial_id');
        $categorias = $this->api->obtenerCategorias();
        $tipos      = $this->api->obtenerTipos();

        return view('clientes.index', compact('comercial', 'categorias', 'tipos'));
    }

    public function detalle(string $codigo)
    {
        $comercial = session('comercial_id');
        $cliente   = $this->api->obtenerCliente($codigo);

        return view('clientes.detalle', compact('comercial', 'cliente'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'nombre_cliente'       => $request->input('busqueda', ''),
            'categoria_cliente'    => $request->input('categoria', ''),
            'provincia'            => $request->input('provincia', ''),
            'poblacion'            => $request->input('poblacion', ''),
            'comercial'            => $comercial,
            'fecha_desde_clientes' => '',
            'fecha_alta'           => '',
            'fecha_visitado'       => '',
        ];

        $clientes = $this->api->buscarClientes($filtros);
        return view('clientes.list', compact('clientes', 'comercial'));
    }
}
