<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class RrhhController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        if (! session()->has('rrhh_id')) {
            return view('rrhh.login');
        }

        $usuario = session('rrhh_id');
        $nombre  = session('rrhh_nombre');

        return view('rrhh.index', compact('usuario', 'nombre'));
    }

    public function inicio()
    {
        if (! session()->has('rrhh_id')) {
            abort(403);
        }

        $html = $this->api->getRHRaw('inicio', ['usuario' => session('rrhh_id')]);

        if ($html === null) {
            return response('<div class="empty-state">No se pudo cargar el módulo de RRHH.</div>', 502);
        }

        return response($html);
    }
}
