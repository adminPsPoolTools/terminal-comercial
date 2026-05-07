<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class GastosController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial   = session('comercial_id');
        $tiposGasto  = $this->api->obtenerTiposGasto();
        $mediosCobro = $this->api->obtenerMediosCobro();

        return view('gastos.index', compact('comercial', 'tiposGasto', 'mediosCobro'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'comercial'      => $comercial,
            'fecha_desde'    => $request->input('fecha_desde', ''),
            'fecha_hasta'    => $request->input('fecha_hasta', ''),
            'comentario'     => $request->input('comentario', ''),
            'tipo_gasto'     => $request->input('tipo_gasto', '0'),
            'medio_cobro'    => $request->input('medio_cobro', '0'),
            'pagado'         => $request->input('pagado', '0'),
        ];

        $gastos = $this->api->obtenerGastos($filtros);
        return view('gastos.list', compact('gastos', 'comercial'));
    }
}
