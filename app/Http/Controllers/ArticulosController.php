<?php
namespace App\Http\Controllers;
use App\Services\ApiService; use Illuminate\Http\Request;

class ArticulosController extends Controller {
    public function __construct(protected ApiService $api) {}
    public function index() {
        $comercial = session('comercial_id');
        return view('articulos.index', compact('comercial'));
    }
    public function list(Request $request) {
        $comercial = session('comercial_id');
        $articulos = $this->api->buscarArticulos(['busqueda' => $request->input('busqueda', ''), 'comercial' => $comercial]);
        return view('articulos.list', compact('articulos', 'comercial'));
    }
}
