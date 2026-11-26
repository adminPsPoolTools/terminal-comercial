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

    /** Estados de agenda que piden fecha de aviso (la API activa la alarma en esa fecha). */
    public const ESTADOS_CON_FECHA_ALARMA = [36, 37, 38];

    /**
     * Ficha de una agenda: datos, historial de estados y cambio de estado.
     * Sustituye a mantenimientos/M_agenda.php del sistema antiguo.
     */
    public function detalle(string $codigo)
    {
        $comercial = (int) session('comercial_id');
        $agenda    = $this->api->obtenerAgenda($codigo);

        if (! $agenda) {
            abort(404, 'Agenda no encontrada');
        }

        $historial       = $this->api->obtenerHistorialEstadosAgenda($codigo);
        $estadosCambio   = $this->api->obtenerEstadosCambioAgenda($comercial);
        $usuarios        = $this->mapa($this->api->obtenerUsuariosVendedores(), 'NOMBRE');
        $estadosAgenda   = $this->mapa($this->api->obtenerEstadosAgenda(), 'DESCRIPCION');
        $caracteres      = $this->mapa($this->api->obtenerCaracteresAgenda(), 'DESCRIPCION');
        $estadosConFecha = self::ESTADOS_CON_FECHA_ALARMA;

        return view('agenda.detalle', compact(
            'agenda', 'codigo', 'historial', 'estadosCambio', 'usuarios',
            'estadosAgenda', 'caracteres', 'estadosConFecha', 'comercial'
        ));
    }

    /**
     * Cambio de estado, igual que el sistema antiguo:
     *  1. Registra la línea en el historial (ESTADOS_REGISTRO_AGENDA) con el estado de expediente elegido.
     *  2. Actualiza la agenda con el estado de agenda asociado, el usuario que debe realizarla y la alarma.
     */
    public function cambiarEstado(Request $request, string $codigo)
    {
        $comercial = (int) session('comercial_id');
        $agenda    = $this->api->obtenerAgenda($codigo);

        if (! $agenda) {
            return back()->with('error', 'No se encontró la agenda.');
        }

        // El estado elegido tiene que ser uno de los permitidos para este comercial
        $estado = null;
        foreach ($this->api->obtenerEstadosCambioAgenda($comercial) as $e) {
            if ((string) $e->CODIGO === (string) $request->input('estado')) {
                $estado = $e;
                break;
            }
        }
        if (! $estado) {
            return back()->withInput()->with('error', 'Selecciona un estado válido.');
        }

        $estadoAgenda    = (int) ($estado->ESTADO_AGENDA_ASOCIADO ?? 0);
        $usuarioAnterior = $agenda->USUARIO ?? $comercial;
        $usuarioRealizar = $usuarioAnterior;

        if (strtoupper(trim((string) ($estado->REALIZAR_POR_USUARIO ?? ''))) === 'S') {
            if (! $request->filled('usuario_realizar_por')) {
                return back()->withInput()->with('error', 'Este estado requiere indicar quién debe realizarla.');
            }
            $usuarioRealizar = (int) $request->input('usuario_realizar_por');
        }

        $fechaAlarma = null;
        if (in_array($estadoAgenda, self::ESTADOS_CON_FECHA_ALARMA, true)) {
            $fechaAlarma = $this->fechaApi($request->input('fecha_alarma'));
            if ($fechaAlarma === '') {
                return back()->withInput()->with('error', 'Este estado requiere una fecha de aviso.');
            }
        }

        $okHistorial = $this->api->registrarEstadoAgenda([
            'agenda'                   => $codigo,
            'estado'                   => $estado->CODIGO,
            'usuario'                  => $comercial,
            'fecha_alta'               => date('Y-m-d'),
            'hora_alta'                => date('H:i:s'),
            'comentario'               => trim((string) $request->input('comentario', '')),
            'expediente'               => $agenda->EXPEDIENTE ?? 0,
            'usuario_realizar_por'     => $usuarioRealizar,
            'usuario_realizar_por_ant' => $usuarioAnterior,
        ]);

        if (! $okHistorial) {
            return back()->withInput()->with('error', 'No se pudo guardar el cambio de estado. Inténtalo de nuevo.');
        }

        $okAgenda = $this->api->actualizarEstadoAgenda([
            'codigo'               => $codigo,
            'estado_agenda'        => $estadoAgenda,
            'usuario_realizar_por' => $usuarioRealizar,
            'fecha_alarma'         => $fechaAlarma,
        ]);

        if (! $okAgenda) {
            return back()->with('error', 'Se registró el cambio en el historial, pero no se pudo actualizar el estado de la agenda.');
        }

        return redirect()->route('agenda.detalle', $codigo)->with('success', 'Estado actualizado correctamente.');
    }

    /** [CODIGO => campo] a partir de un listado de la API. */
    private function mapa(array $lista, string $campo): array
    {
        $res = [];
        foreach ($lista as $o) {
            $res[trim((string) ($o->CODIGO ?? ''))] = $o->{$campo} ?? '';
        }
        return $res;
    }
}
