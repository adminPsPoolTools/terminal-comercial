<?php

namespace App\Http\Controllers;

use App\Services\ApiService;

class SolicitudesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function detalle(string $codigo)
    {
        $solicitud = $this->api->obtenerDetalleSolicitud($codigo);
        return view('solicitudes.detalle', compact('solicitud', 'codigo'));
    }
}
