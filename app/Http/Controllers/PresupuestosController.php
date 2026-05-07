<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PresupuestosController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial  = session('comercial_id');
        $estados    = $this->api->obtenerEstadosPresupuesto();
        $provincias = $this->api->obtenerProvincias();

        return view('presupuestos.index', compact('comercial', 'estados', 'provincias'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'cliente'       => '',
            'fecha_desde'   => $request->input('fecha_desde', date('01/01/Y')),
            'titulo'        => $request->input('titulo', ''),
            'estado'        => $request->input('estado', ''),
            'comercial'     => $comercial,
            'provincia'     => $request->input('provincia', ''),
            'poblacion'     => $request->input('poblacion', ''),
            'proyecto'      => $request->input('proyecto', '0'),
            'caracter'      => $request->input('caracter', '0'),
            'desc_articulo' => $request->input('desc_articulo', ''),
            'orden'         => $request->input('orden', 'codigo'),
            'orden_ori'     => $request->input('orden_ori', 'desc'),
        ];

        $presupuestos = $this->api->obtenerPresupuestos($filtros);
        return view('presupuestos.list', compact('presupuestos', 'comercial'));
    }

    public function poblaciones(Request $request)
    {
        $poblaciones = $this->api->obtenerPoblaciones($request->input('provincia', ''));
        return view('partials.select_poblaciones', compact('poblaciones'));
    }
}
