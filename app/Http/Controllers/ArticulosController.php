<?php
namespace App\Http\Controllers;
use App\Services\ApiService; use Illuminate\Http\Request;

class ArticulosController extends Controller {
    /** Resultados por página que devuelve la API (fijo en ArticulosController@buscarArticulos de la API). */
    const POR_PAGINA = 100;

    public function __construct(protected ApiService $api) {}
    public function index() {
        $comercial = session('comercial_id');
        return view('articulos.index', compact('comercial'));
    }
    public function list(Request $request) {
        $comercial = session('comercial_id');
        $pagina    = max(1, (int) $request->input('pagina', 1));
        // La API lee "filtro" y "pagina" (sin "pagina" calcula SKIP negativo y falla)
        $articulos = $this->api->buscarArticulos([
            'filtro' => trim((string) $request->input('busqueda', '')),
            'pagina' => $pagina,
        ]);
        $hayMas = count($articulos) >= self::POR_PAGINA;
        return view('articulos.list', compact('articulos', 'comercial', 'pagina', 'hayMas'));
    }
}
