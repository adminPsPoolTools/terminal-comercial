<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ListadosController extends Controller
{
    /**
     * Listados de la API (ListadosController de ApiGesplanet_TC). Todos reciben
     * fecha_desde, fecha_hasta y comercial.
     * Columnas: [campo, etiqueta, tipo] con tipo text|money|int|date|cliente
     * ("cliente" enlaza a la ficha usando el valor del campo como código).
     */
    public const LISTADOS = [
        'ventas-clientes' => [
            'titulo'   => 'Ventas clientes por provincias',
            'endpoint' => 'obtenerVentasClientesProvincias',
            'columnas' => [
                ['PROVINCIA', 'Provincia', 'text'],
                ['CODIGO', 'Código', 'cliente'],
                ['DESCRIPCION', 'Cliente', 'text'],
                ['TIMPORTEBASE', 'Base imponible', 'money'],
            ],
        ],
        'ventas-secciones' => [
            'titulo'   => 'Ventas artículos por secciones',
            'endpoint' => 'obtenerVentasSecciones',
            'columnas' => [
                ['DESCRIPCION_SECCION', 'Sección', 'text'],
                ['ARTICULO', 'Artículo', 'text'],
                ['DESCRIPCION_ARTICULO', 'Descripción', 'text'],
                ['TIMPORTEBASE', 'Base imponible', 'money'],
            ],
        ],
        'clientes-nuevos' => [
            'titulo'   => 'Clientes nuevos',
            'endpoint' => 'obtenerNuevosClientes',
            'columnas' => [
                ['CODIGO', 'Código', 'cliente'],
                ['DESCRIPCION', 'Cliente', 'text'],
                ['FECHA_ALTA', 'Fecha alta', 'date'],
                ['POBLACION', 'Población', 'text'],
                ['PROVINCIA', 'Provincia', 'text'],
                ['TELEFONOFIJO', 'Teléfono', 'text'],
            ],
        ],
        'ventas-provincias' => [
            'titulo'   => 'Ventas por provincias',
            'endpoint' => 'obtenerVentasProvincias',
            'columnas' => [
                ['PROVINCIA', 'Provincia', 'text'],
                ['VENTAS', 'Ventas', 'money'],
                ['CLIENTES_TOTAL', 'Clientes', 'int'],
                ['CLIENTES_NUEVOS', 'Clientes nuevos', 'int'],
                ['VISITAS_COMERCIALES', 'Visitas', 'int'],
                ['PRESUPUESTOS_REALIZADOS', 'Presup. realizados', 'int'],
                ['PRESUPUESTOS_APROBADOS', 'Presup. aprobados', 'int'],
            ],
        ],
        'ranking-ventas' => [
            'titulo'   => 'Ranking ventas (top 10)',
            'endpoint' => 'obtenerRankingVentas',
            'columnas' => [
                ['CLIENTE', 'Código', 'cliente'],
                ['DESCRIPCION', 'Cliente', 'text'],
                ['PROVINCIA', 'Provincia', 'text'],
                ['DISTANCIA', 'Distancia', 'int'],
                ['VISITAS', 'Visitas', 'int'],
                ['VENTAS', 'Ventas', 'money'],
            ],
        ],
        'ranking-visitas' => [
            'titulo'   => 'Ranking visitas comerciales (top 10)',
            'endpoint' => 'obtenerRankingVisitas',
            'columnas' => [
                ['CLIENTE', 'Código', 'cliente'],
                ['DESCRIPCION', 'Cliente', 'text'],
                ['PROVINCIA', 'Provincia', 'text'],
                ['DISTANCIA', 'Distancia', 'int'],
                ['VISITAS', 'Visitas', 'int'],
                ['VENTAS', 'Ventas', 'money'],
            ],
        ],
        'control-visitas' => [
            'titulo'   => 'Control de visitas',
            'endpoint' => 'obtenerControlVisitasExtendido',
            'columnas' => [
                ['CLIENTE', 'Código', 'cliente'],
                ['DESCRIPCION', 'Cliente', 'text'],
                ['PROVINCIA', 'Provincia', 'text'],
                ['EXPEDIENTE', 'Expediente', 'text'],
                ['VISITAS', 'Visitas', 'int'],
                ['VENTAS', 'Ventas', 'money'],
            ],
        ],
        'facturas-clientes' => [
            'titulo'   => 'Facturas clientes',
            'endpoint' => 'obtenerFacturasClientes',
            'columnas' => [
                ['SERIE', 'Serie', 'text'],
                ['NUMERO_FACTURA', 'Factura', 'text'],
                ['FECHA', 'Fecha', 'date'],
                ['CLIENTE', 'Código', 'cliente'],
                ['DES_CLIENTE', 'Cliente', 'text'],
                ['IMPORTE', 'Importe', 'money'],
            ],
        ],
    ];

    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial = session('comercial_id');
        $listados  = self::LISTADOS;
        return view('listados.index', compact('comercial', 'listados'));
    }

    public function ver(Request $request, string $tipo)
    {
        $def = self::LISTADOS[$tipo] ?? abort(404);
        [$desde, $hasta] = $this->rango($request);

        $filtros = [
            'fecha_desde' => $desde,
            'fecha_hasta' => $hasta,
            'comercial'   => (int) session('comercial_id'),
        ];

        $res     = $this->api->listado($def['endpoint'], $filtros);
        $filas   = $this->filas($tipo, $res);
        $resumen = [];

        if ($tipo === 'control-visitas') {
            // Resumen: nº de clientes según cuántas visitas han recibido
            $control = $this->api->listado('obtenerControlVisitas', $filtros);
            if (is_object($control)) {
                $resumen['Clientes visitados'] = $control->CLIENTES_VISITADOS ?? 0;
                foreach ((array) ($control->VISITAS ?? []) as $v) {
                    $n = (int) ($v->CANTIDAD_VISITAS_COMERCIALES ?? 0);
                    $resumen['Clientes con ' . $n . ' ' . ($n === 1 ? 'visita' : 'visitas')] = $v->CLIENTES ?? 0;
                }
            }
        }

        return view('listados.tabla', [
            'columnas' => $def['columnas'],
            'filas'    => $filas,
            'resumen'  => $resumen,
            'error'    => $res === null,
            'periodo'  => date('d/m/Y', strtotime($desde)) . ' – ' . date('d/m/Y', strtotime($hasta)),
        ]);
    }

    public function clientes(Request $request)
    {
        $comercial = session('comercial_id');
        $filtros   = [
            'comercial'            => $comercial,
            'nombre_cliente'       => $request->input('nombre_cliente', ''),
            'categoria_cliente'    => $request->input('categoria_cliente', ''),
            'provincia'            => $request->input('provincia', ''),
            'poblacion'            => $request->input('poblacion', ''),
            // Clientes con ventas desde la fecha indicada (obtenerListadoClientes)
            'fecha_desde_clientes' => $this->fechaApi($request->input('fecha_desde')),
            'fecha_alta'           => '',
            'fecha_visitado'       => '',
        ];
        $clientes  = $this->api->listadoClientes($filtros);
        return view('listados.clientes', compact('clientes', 'comercial'));
    }

    /**
     * Rango de fechas en formato API. "anual" = año completo de la fecha desde;
     * "mensual" = fechas indicadas (por defecto, mes actual).
     */
    private function rango(Request $request): array
    {
        $desde = $this->fechaApi($request->input('fecha_desde'), date('01/m/Y'));
        $hasta = $this->fechaApi($request->input('fecha_hasta'), date('t/m/Y'));

        if ($request->input('tipo') === 'anual') {
            $anio  = (int) substr($desde, 0, 4) ?: (int) date('Y');
            $desde = $this->fechaApi("01/01/{$anio}");
            $hasta = $this->fechaApi("31/12/{$anio}");
        }

        return [$desde, $hasta];
    }

    /** Convierte la respuesta de la API en una lista de filas (objetos). */
    private function filas(string $tipo, mixed $res): array
    {
        if ($tipo === 'ventas-provincias') {
            // La API devuelve [filasProvincias, {PROVINCIA: {CLIENTES_NUEVOS, ...}}]
            if (! is_array($res) || count($res) < 2) {
                return [];
            }
            [$provincias, $detalle] = $res;
            $detalle = (array) $detalle;

            return array_map(function ($p) use ($detalle) {
                return (object) array_merge((array) $p, (array) ($detalle[$p->PROVINCIA ?? ''] ?? []));
            }, (array) $provincias);
        }

        return is_array($res) ? array_values(array_filter($res, 'is_object')) : [];
    }
}
