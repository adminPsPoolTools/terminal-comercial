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
            // La API lee fecha_gasto_desde / fecha_gasto_hasta (HG.FECHA BETWEEN ...);
            // con otros nombres el BETWEEN recibe NULL y nunca devuelve gastos.
            // Por defecto, el último mes (para no cargar todo el histórico)
            'fecha_gasto_desde' => $this->fechaApi($request->input('fecha_desde'), date('Y-m-d', strtotime('-1 month'))),
            'fecha_gasto_hasta' => $this->fechaApi($request->input('fecha_hasta'), date('Y-m-d')),
            'comentario'     => $request->input('comentario', ''),
            'tipo_gasto'     => $request->input('tipo_gasto', '0'),
            'medio_cobro'    => $request->input('medio_cobro', '0'),
            'pagado'         => $request->input('pagado', '0'),
        ];

        $gastos = $this->api->obtenerGastos($filtros);
        return view('gastos.list', compact('gastos', 'comercial'));
    }
}
