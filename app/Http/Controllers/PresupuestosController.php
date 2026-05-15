<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PresupuestosController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial  = session('comercial_id');
        $estados    = $this->api->obtenerEstadosPresupuesto();
        $provincias = $this->api->obtenerProvincias();

        return view('presupuestos.index', compact('comercial', 'estados', 'provincias'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'cliente'       => $request->input('cliente', ''),
            'fecha_desde'   => $request->input('fecha_desde', date('01/01/Y')),
            'titulo'        => $request->input('titulo', ''),
            'estado'        => $request->input('estado', ''),
            'comercial'     => $comercial,
            'provincia'     => $request->input('provincia', ''),
            'poblacion'     => $request->input('poblacion', ''),
            'proyecto'      => $request->input('proyecto', '0'),
            'caracter'      => $request->input('caracter', '0'),
            'desc_articulo' => $request->input('desc_articulo', ''),
            'orden'         => $request->input('orden', 'codigo'),
            'orden_ori'     => $request->input('orden_ori', 'desc'),
        ];

        $presupuestos = $this->api->obtenerPresupuestos($filtros);
        $hideCliente  = !empty($filtros['cliente']);
        return view('presupuestos.list', compact('presupuestos', 'comercial', 'hideCliente'));
    }

    public function detalle(string $codigo)
    {
        $cabecera      = $this->api->obtenerDetallePresupuesto($codigo);
        $lineas        = $this->api->obtenerLineasPresupuesto($codigo);
        $estadoAct     = $this->api->obtenerEstadoActualPresupuesto($codigo);
        $estados       = $this->api->obtenerEstadosPresupuesto();
        $estadoActual  = $estadoAct?->ESTADO ?? '';
        $comentarioAct = $estadoAct?->COMENTARIO_COMERCIAL_PRESU ?? '';
        return view('presupuestos.detalle', compact('cabecera', 'lineas', 'codigo', 'estados', 'estadoActual', 'comentarioAct'));
    }

    public function estado(string $codigo)
    {
        $obj    = $this->api->obtenerEstadoActualPresupuesto($codigo);
        $estado = $obj?->ESTADO ?? '';
        $cls    = match(true) {
            str_contains(strtolower($estado), 'acept')  => 'badge-green',
            str_contains(strtolower($estado), 'rechaz') => 'badge-red',
            str_contains(strtolower($estado), 'espera') => 'badge-yellow',
            default => 'badge-gray',
        };
        $badge = $estado ? "<span class=\"badge {$cls}\">{$estado}</span>" : '<span class="text-slate-300 text-xs">—</span>';
        return response($badge)->header('Content-Type', 'text/html');
    }

    public function update(Request $request, string $codigo)
    {
        $ok = $this->api->actualizarPresupuesto(
            $codigo,
            $request->input('estado', ''),
            $request->input('comentario', '')
        );

        if ($ok) {
            return redirect()->route('presupuestos.detalle', $codigo)
                ->with('success', 'Presupuesto actualizado correctamente.');
        }

        return back()->withInput()->with('error', 'Error al actualizar el presupuesto.');
    }

    public function poblaciones(Request $request)
    {
        $poblaciones = $this->api->obtenerPoblaciones($request->input('provincia', ''));
        return view('partials.select_poblaciones', compact('poblaciones'));
    }
}
