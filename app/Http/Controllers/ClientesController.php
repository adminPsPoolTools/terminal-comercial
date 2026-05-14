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
        $busqueda  = trim($request->input('busqueda', ''));

        // Numeric-only input is treated as a client code; text as a name search
        $filtros = array_filter([
            'nombre_cliente'    => !ctype_digit($busqueda) ? ($busqueda ?: null) : null,
            'codigo_cliente'    => ctype_digit($busqueda)  ? $busqueda           : null,
            'categoria_cliente' => $request->input('categoria', '') ?: null,
            'provincia'         => $request->input('provincia', '') ?: null,
            'poblacion'         => $request->input('poblacion', '') ?: null,
            'comercial'         => $comercial,
        ]);

        $clientes = $this->api->buscarClientes($filtros);
        return view('clientes.list', compact('clientes', 'comercial'));
    }
}
