<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected ApiService $api) {}

    // ── Login comercial ──────────────────────────────────────────
    public function loginComercial(Request $request)
    {
        $comercial = $request->input('comercial');
        $clave     = $request->input('clave');

        $row = $this->api->loginVendedor($comercial);

        if (! $row) {
            return response()->json(['ok' => false, 'mensaje' => 'Usuario no encontrado']);
        }

        if (trim($row->CLAVE ?? '') !== trim($clave)) {
            return response()->json(['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
        }

        session([
            'comercial_id'     => (int) $comercial,
            'comercial_nombre' => $row->NOMBRE ?? $comercial,
            'comercial_tipo'   => 'vendedor',
        ]);

        return response()->json([
            'ok'     => true,
            'nombre' => $row->NOMBRE ?? $comercial,
        ]);
    }

    // ── Login RRHH ───────────────────────────────────────────────
    public function loginRrhh(Request $request)
    {
        $comercial = $request->input('comercial');
        $clave     = $request->input('clave');

        $row = $this->api->loginEmpleado($comercial);

        if (! $row) {
            return response()->json(['ok' => false, 'mensaje' => 'Usuario no encontrado']);
        }

        if (trim($row->PASSWORD ?? '') !== trim($clave)) {
            return response()->json(['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos']);
        }

        session([
            'rrhh_id'     => (int) $comercial,
            'rrhh_nombre' => $row->NOMBRE ?? $comercial,
        ]);

        return response()->json([
            'ok'     => true,
            'nombre' => $row->NOMBRE ?? $comercial,
        ]);
    }

    // ── Logout ───────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('home');
    }

    // ── Alarmas (polling) ────────────────────────────────────────
    public function alarmas(Request $request)
    {
        $comercial = session('comercial_id');
        if (! $comercial) return response()->json(['alarmas' => 0, 'recordatorios' => 0]);

        return response()->json([
            'alarmas'       => $this->api->contarAlarmas($comercial, 'alarma'),
            'recordatorios' => $this->api->contarAlarmas($comercial, 'recordatorio'),
        ]);
    }

    // ── Alarmas técnico ──────────────────────────────────────────
    public function alarmasTecnico()
    {
        $comercial = config('crm.comercial_tecnico');
        return response()->json([
            'alarmas' => $this->api->contarAlarmas($comercial, 'alarma'),
        ]);
    }
}
