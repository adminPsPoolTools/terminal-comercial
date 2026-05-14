<?php

namespace App\Http\Controllers;

use App\Services\ApiService;

class VisitasController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function detalle(string $codigo)
    {
        $visita   = $this->api->obtenerDetalleVisita($codigo);
        $acciones = $this->api->obtenerAccionesVisita($codigo);
        $asuntos  = $this->api->obtenerAsuntosVisita($codigo);
        return view('visitas.detalle', compact('visita', 'acciones', 'asuntos', 'codigo'));
    }
}
