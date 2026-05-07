<?php
namespace App\Http\Controllers;
use App\Services\ApiService; use Illuminate\Http\Request;

class ObjetivosController extends Controller {
    public function __construct(protected ApiService $api) {}
    public function index() {
        $comercial = session('comercial_id');
        $objetivos = $this->api->listadoObjetivos(['comercial' => $comercial]);
        return view('objetivos.index', compact('comercial', 'objetivos'));
    }
}
