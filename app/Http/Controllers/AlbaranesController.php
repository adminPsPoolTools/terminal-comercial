<?php

namespace App\Http\Controllers;

use App\Services\ApiService;

class AlbaranesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function detalle(string $codigo)
    {
        $cabecera = $this->api->obtenerDetalleAlbaran($codigo);
        $lineas   = $this->api->obtenerLineasAlbaran($codigo);
        return view('albaranes.detalle', compact('cabecera', 'lineas', 'codigo'));
    }
}
