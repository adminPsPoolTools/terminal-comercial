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

        // Obtener código nuevo antes de crear
        $codigoObj = $this->api->obtenerNuevoCodigoVisita();
        $codigo    = $codigoObj?->ULTIMOCODIGO ?? null;

        if (!$codigo) {
            return back()->withInput()->with('error', 'No se pudo obtener un código para la visita.');
        }

        // Campos opcionales: enviar 0 si vacíos (SQL Server no acepta null en esas columnas)
        $datos = [
            'vendedor'          => $comercial,
            'cliente'           => $cliente,
            'proyecto'          => $request->input('proyecto', '1'),
            'fecha'             => $request->input('fecha', date('Y-m-d')),
            'hora'              => $request->input('hora', date('H:i')),
            'tipo'              => $request->input('tipo', '0'),
            'motivo'            => $request->input('motivo') ?: '0',
            'comentario_motivo' => $request->input('comentario_motivo', ''),
            'contacto'          => $request->input('contacto') ?: '0',
            'lineaDireccion'    => $request->input('lineaDireccion') ?: '0',
            'comentario'        => $request->input('comentario', ''),
            'codigo'            => $codigo,
        ];

        $res = $this->api->crearVisitaComercial($datos);

        if ($res !== null) {
            // Asignar acciones y asuntos en llamadas separadas
            $acciones = implode(',', $request->input('acciones', []));
            $asuntos  = implode(',', $request->input('asuntos', []));
            if ($acciones) $this->api->asignarAccionesVisita((string) $codigo, $acciones);
            if ($asuntos)  $this->api->asignarAsuntosVisita((string) $codigo, $asuntos);

            return $cliente
                ? redirect()->route('clientes.detalle', $cliente)->with('success', 'Visita creada correctamente.')
                : redirect()->route('visitas.detalle', $codigo)->with('success', 'Visita creada correctamente.');
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
