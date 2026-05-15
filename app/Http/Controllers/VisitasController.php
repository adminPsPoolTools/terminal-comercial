<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class VisitasController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function crear(Request $request)
    {
        $cliente    = $request->input('cliente', '');
        $comercial  = (int) session('comercial_id');
        $motivos    = $this->api->obtenerMotivosVisitas();
        $acciones   = $this->api->obtenerListaAccionesVisitas();
        $asuntos    = $this->api->obtenerListaAsuntosVisitas();
        $contactos  = $cliente ? $this->api->obtenerContactosCliente($cliente, $comercial) : [];
        $direcciones = $cliente ? $this->api->obtenerDireccionesCliente($cliente) : [];

        return view('visitas.crear', compact('cliente', 'comercial', 'motivos', 'acciones', 'asuntos', 'contactos', 'direcciones'));
    }

    public function store(Request $request)
    {
        $comercial = (int) session('comercial_id');
        $cliente   = $request->input('cliente', '');

        $datos = [
            'vendedor'          => $comercial,
            'cliente'           => $cliente,
            'proyecto'          => $request->input('proyecto', '1'),
            'fecha'             => $request->input('fecha', date('d/m/Y')),
            'hora'              => $request->input('hora', date('H:i')),
            'tipo'              => $request->input('tipo', '0'),
            'motivo'            => $request->input('motivo', ''),
            'comentario_motivo' => $request->input('comentario_motivo', ''),
            'contacto'          => $request->input('contacto', ''),
            'lineaDireccion'    => $request->input('lineaDireccion', ''),
            'comentario'        => $request->input('comentario', ''),
            'acciones'          => implode(',', $request->input('acciones', [])),
            'asuntos'           => implode(',', $request->input('asuntos', [])),
            'codigo'            => '',
        ];

        $res = $this->api->crearVisitaComercial($datos);

        if ($res) {
            $destino = $cliente
                ? redirect()->route('clientes.detalle', $cliente)->with('success', 'Visita creada correctamente.')
                : redirect()->route('visitas.detalle', $res->CODIGO ?? $res->codigo ?? 0)->with('success', 'Visita creada correctamente.');
            return $destino;
        }

        return back()->withInput()->with('error', 'Error al crear la visita.');
    }

    public function detalle(string $codigo)
    {
        $visita   = $this->api->obtenerDetalleVisita($codigo);
        $acciones = $this->api->obtenerAccionesVisita($codigo);
        $asuntos  = $this->api->obtenerAsuntosVisita($codigo);
        return view('visitas.detalle', compact('visita', 'acciones', 'asuntos', 'codigo'));
    }
}
