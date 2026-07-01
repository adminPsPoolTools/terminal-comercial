<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct(protected ApiService $api) {}

    public function index()
    {
        $comercial  = session('comercial_id');
        $categorias = $this->api->obtenerCategorias();
        $tipos      = $this->api->obtenerTipos();

        return view('clientes.index', compact('comercial', 'categorias', 'tipos'));
    }

    public function detalle(string $codigo)
    {
        $comercial = session('comercial_id');
        $cliente   = $this->api->obtenerCliente($codigo);

        return view('clientes.detalle', compact('comercial', 'cliente', 'codigo'));
    }

    public function list(Request $request)
    {
        $comercial = session('comercial_id');

        $filtros = [
            'nombre_cliente'       => $request->input('busqueda', ''),
            'categoria_cliente'    => $request->input('categoria', ''),
            'provincia'            => $request->input('provincia', ''),
            'poblacion'            => $request->input('poblacion', ''),
            'comercial'            => $comercial,
            'fecha_desde_clientes' => '',
            'fecha_alta'           => '',
            'fecha_visitado'       => '',
        ];

        $clientes = $this->api->buscarClientes($filtros);
        return view('clientes.list', compact('clientes', 'comercial'));
    }

    public function crear()
    {
        $categorias = $this->api->obtenerCategorias();
        $tipos      = $this->api->obtenerTipos();
        $provincias = $this->api->obtenerProvincias();

        return view('clientes.crear', compact('categorias', 'tipos', 'provincias'));
    }

    public function store(Request $request)
    {
        $comercial = (int) session('comercial_id');

        $esExt = $request->input('es_extracomunitario', 'N');
        $impuesto = match($esExt) {
            'I', 'E' => '99',
            'N'      => '21',
            default  => '0',
        };

        $datos = [
            'comercial'                       => $comercial,
            'codigo'                          => 0,
            'cif'                             => $request->input('cif', ''),
            'categoria'                       => $request->input('categoria', ''),
            'tipocliente'                     => $request->input('tipocliente', ''),
            'descripcion'                     => $request->input('descripcion', ''),
            'alias'                           => $request->input('alias', ''),
            'direccion'                       => $request->input('direccion', ''),
            'apcorreos'                       => $request->input('apcorreos', ''),
            'cp'                              => $request->input('cp', ''),
            'poblacion'                       => $request->input('poblacion', ''),
            'comarca'                         => $request->input('comarca', ''),
            'provincia'                       => $request->input('provincia', ''),
            'pais'                            => $request->input('pais', ''),
            'telefonofijo'                    => $request->input('telefonofijo', ''),
            'telefonomovil'                   => $request->input('telefonomovil', ''),
            'fax'                             => $request->input('fax', ''),
            'correo'                          => $request->input('correo', ''),
            'web'                             => $request->input('web', ''),
            'contacto'                        => $request->input('contacto', ''),
            'comentario_comercial_cliente'    => $request->input('comentario_comercial_cliente', ''),
            'comentario_alerta_comercial'     => $request->input('comentario_alerta_comercial', ''),
            'libre10_cliente'                 => $request->input('libre10_cliente', ''),
            'direccionenviomercancia'         => $request->input('direccionenviomercancia', ''),
            'cpenviomercancia'                => $request->input('cpenviomercancia', ''),
            'poblacionenviomercancia'         => $request->input('poblacionenviomercancia', ''),
            'provinciaenviomercancia'         => $request->input('provinciaenviomercancia', ''),
            'telefonofijoenviomercancia'      => $request->input('telefonofijoenviomercancia', ''),
            'telefonomovilenviomercancia'     => $request->input('telefonomovilenviomercancia', ''),
            'faxenviomercancia'               => $request->input('faxenviomercancia', ''),
            'correoenviomercancia'            => $request->input('correoenviomercancia', ''),
            'vendedor'                        => $comercial,
            'vendedor_asignado'               => $comercial,
            'usuario_alta'                    => $comercial,
            'usuario_ultima_modificacion'     => $comercial,
            'es_extracomunitario'             => $esExt,
            'impuesto'                        => $impuesto,
            'temas_interes'                   => '',
        ];

        $resultado = $this->api->crearCliente($datos);

        if ($resultado && !empty($resultado->codigo)) {
            return redirect()->route('clientes.detalle', $resultado->codigo)
                ->with('success', 'Cliente creado correctamente.');
        }

        return back()->withInput()->with('error', 'Error al crear el cliente. Comprueba los datos e inténtalo de nuevo.');
    }

    public function tabContactos(string $codigo)
    {
        $comercial  = (int) session('comercial_id');
        $contactos  = $this->api->obtenerContactosCliente($codigo, $comercial);
        return view('clientes.tabs.contactos', compact('contactos'));
    }

    public function tabDirecciones(string $codigo)
    {
        $direcciones = $this->api->obtenerDireccionesCliente($codigo);
        return view('clientes.tabs.direcciones', compact('direcciones'));
    }

    public function tabVisitas(string $codigo)
    {
        $comercial = (int) session('comercial_id');
        $visitas   = $this->api->obtenerVisitasCliente($codigo, $comercial);
        return view('clientes.tabs.visitas', compact('visitas', 'codigo'));
    }

    public function tabIncidencias(string $codigo)
    {
        $incidencias = $this->api->obtenerIncidenciasCliente($codigo);
        return view('clientes.tabs.incidencias', compact('incidencias'));
    }

    public function tabSolicitudes(string $codigo, Request $request)
    {
        $filtro      = $request->input('filtro', '');
        $solicitudes = $this->api->obtenerSolicitudesPresupuestoCliente($codigo, $filtro);
        return view('clientes.tabs.solicitudes', compact('solicitudes', 'codigo'));
    }

    public function tabVentasSgfa(string $codigo, Request $request)
    {
        $fechaDesde = $request->input('fecha_desde', date('Y-01-01'));
        $ventas     = $this->api->obtenerVentasSgfa($fechaDesde, $codigo);
        return view('clientes.tabs.ventas_sgfa', compact('ventas', 'fechaDesde'));
    }

    public function tabArticulosVendidos(string $codigo, Request $request)
    {
        $fechaDesde = $request->input('fecha_desde', date('Y-01-01'));
        $articulos  = $this->api->obtenerArticulosVendidos($fechaDesde, $codigo);
        return view('clientes.tabs.articulos_vendidos', compact('articulos', 'fechaDesde'));
    }

    public function tabLlamadas(string $codigo)
    {
        $llamadas = $this->api->obtenerLlamadasCliente($codigo);
        return view('clientes.tabs.llamadas', compact('llamadas'));
    }

    public function tabAlbaranes(string $codigo, Request $request)
    {
        $comercial  = (int) session('comercial_id');
        $fechaDesde = $request->input('fecha_desde', date('Y-01-01'));
        $albaranes  = $this->api->obtenerAlbaranesCliente($fechaDesde, $comercial, $codigo);
        return view('clientes.tabs.albaranes', compact('albaranes', 'fechaDesde'));
    }

    public function tabHorarios(string $codigo)
    {
        $horarios = $this->api->obtenerHorariosCliente($codigo);
        return view('clientes.tabs.horarios', compact('horarios'));
    }
}
