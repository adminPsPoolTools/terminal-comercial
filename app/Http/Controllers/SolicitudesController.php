<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function crear(Request $request)
    {
        $cliente    = $request->input('cliente', '');
        $proyectos  = $this->api->obtenerProyectosSolicitud();
        $caracteres = $this->api->obtenerCaracteresSolicitud();
        $categorias = $this->api->obtenerCategoriasSolicitud();
        $correos    = $cliente ? $this->api->obtenerCorreosCliente($cliente) : [];

        return view('solicitudes.crear', compact('cliente', 'proyectos', 'caracteres', 'categorias', 'correos'));
    }

    public function store(Request $request)
    {
        $comercial = (int) session('comercial_id');
        $cliente   = $request->input('cliente', '');

        $codigoObj   = $this->api->obtenerSiguienteCodigoSolicitud();
        $nuevoCodigo = $codigoObj?->NUEVO_CODIGO ?? null;

        if (!$nuevoCodigo) {
            return back()->withInput()->with('error', 'No se pudo obtener un código para la solicitud.');
        }

        $datos = [
            'nuevo_codigo'   => $nuevoCodigo,
            'fecha'          => $request->input('fecha', date('Y-m-d')),
            'hora'           => $request->input('hora', date('H:i')),
            'comercial'      => $comercial,
            'proyecto'       => $request->input('proyecto', '1'),
            'cliente'        => $cliente,
            'caracter'       => $request->input('caracter', '2'),
            'categoria'      => $request->input('categoria', ''),
            'correo'         => $request->input('correo', ''),
            'plantilla'      => $request->input('plantilla', ''),
            'expediente'     => $request->input('expediente', '0') ?: '0',
            'visitaComercial' => null,
        ];

        $res = $this->api->crearSolicitudPresupuesto($datos);

        if ($res !== null) {
            return $cliente
                ? redirect()->route('clientes.detalle', $cliente)->with('success', 'Solicitud creada correctamente.')
                : redirect()->route('solicitudes.detalle', $nuevoCodigo)->with('success', 'Solicitud creada correctamente.');
        }

        return back()->withInput()->with('error', 'Error al crear la solicitud.');
    }

    public function detalle(string $codigo)
    {
        $solicitud = $this->api->obtenerDetalleSolicitud($codigo);
        return view('solicitudes.detalle', compact('solicitud', 'codigo'));
    }
}
