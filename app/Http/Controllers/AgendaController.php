<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial = session('comercial_id');
        $estados   = $this->api->obtenerEstadosExpediente();
        $usuarios  = [];

        if (in_array($comercial, config('crm.admin_comerciales'))) {
            $usuarios = $this->api->obtenerUsuariosVendedores();
        }

        return view('agenda.index', compact('comercial', 'estados', 'usuarios'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'fecha_desde'    => $this->fechaApi($request->input('fecha_desde'), date('Y-m-d', strtotime('-1 month'))), // por defecto, último mes
            'titulo'         => $request->input('titulo', ''),
            'realizado'      => $request->input('realizado', ''),
            'con_alarma'     => $request->input('con_alarma', ''),
            'recordatorio'   => $request->input('recordatorio', ''),
            'estado'         => $request->input('estado', ''),
            'comercial'      => $comercial,
            // Solo los administradores (crm.admin_comerciales) pueden consultar otro usuario
            'usuario'        => $this->usuarioConsultado($request->input('usuario')),
            'codigo_cliente' => $request->input('codigo_cliente', ''),
        ];

        $agendas = $this->api->buscarAgendas($filtros);
        return view('agenda.list', compact('agendas', 'comercial'));
    }
}
