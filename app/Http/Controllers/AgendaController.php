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
            'fecha_desde'    => $this->toFirebirdDate($request->input('fecha_desde', '01/01/2000')),
            'titulo'         => $request->input('titulo', ''),
            'realizado'      => $request->input('realizado', ''),
            'con_alarma'     => $request->input('con_alarma', ''),
            'recordatorio'   => $request->input('recordatorio', ''),
            'estado'         => $request->input('estado', ''),
            'comercial'      => $comercial,
            'usuario'        => $request->input('usuario', $comercial),
            'codigo_cliente' => $request->input('codigo_cliente', ''),
        ];

        $agendas = $this->api->buscarAgendas($filtros);
        return view('agenda.list', compact('agendas', 'comercial'));
    }

    private function toFirebirdDate(string $fecha): string
    {
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            return substr($fecha,3,2) . '/' . substr($fecha,0,2) . '/' . substr($fecha,6,4);
        }
        return $fecha;
    }
}
