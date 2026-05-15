<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlbaranesController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ExpedientesController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\IncidenciasController;
use App\Http\Controllers\ListadosController;
use App\Http\Controllers\ObjetivosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PresupuestosController;
use App\Http\Controllers\RrhhController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\VisitasController;
use Illuminate\Support\Facades\Route;

// ── Home / Login ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

// ── Auth API endpoints ────────────────────────────────────────────────────────
Route::post('/auth/login',          [AuthController::class, 'loginComercial'])->name('auth.login');
Route::post('/auth/login-rrhh',     [AuthController::class, 'loginRrhh'])->name('auth.login.rrhh');
Route::post('/auth/logout',         [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/alarmas',         [AuthController::class, 'alarmas'])->name('auth.alarmas');
Route::get('/auth/alarmas-tecnico', [AuthController::class, 'alarmasTecnico'])->name('auth.alarmas.tecnico');

// ── Rutas protegidas ──────────────────────────────────────────────────────────
Route::middleware('auth.comercial')->group(function () {

    // Agenda
    Route::get('/agenda',       [AgendaController::class, 'index'])->name('agenda.index');
    Route::get('/agenda/list',  [AgendaController::class, 'list'])->name('agenda.list');

    // Clientes
    Route::get('/clientes',           [ClientesController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/list',      [ClientesController::class, 'list'])->name('clientes.list');
    Route::get('/clientes/crear',     [ClientesController::class, 'crear'])->name('clientes.crear');
    Route::post('/clientes',          [ClientesController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{codigo}',  [ClientesController::class, 'detalle'])->name('clientes.detalle');

    // Artículos
    Route::get('/articulos',      [ArticulosController::class, 'index'])->name('articulos.index');
    Route::get('/articulos/list', [ArticulosController::class, 'list'])->name('articulos.list');

    // Presupuestos
    Route::get('/presupuestos',             [PresupuestosController::class, 'index'])->name('presupuestos.index');
    Route::get('/presupuestos/list',        [PresupuestosController::class, 'list'])->name('presupuestos.list');
    Route::get('/presupuestos/poblaciones', [PresupuestosController::class, 'poblaciones'])->name('presupuestos.poblaciones');
    Route::get('/presupuestos/{codigo}',            [PresupuestosController::class, 'detalle'])->name('presupuestos.detalle');
    Route::get('/presupuestos/{codigo}/estado',     [PresupuestosController::class, 'estado'])->name('presupuestos.estado');
    Route::post('/presupuestos/{codigo}/actualizar',[PresupuestosController::class, 'update'])->name('presupuestos.update');

    // Expedientes
    Route::get('/expedientes',      [ExpedientesController::class, 'index'])->name('expedientes.index');
    Route::get('/expedientes/list', [ExpedientesController::class, 'list'])->name('expedientes.list');

    // Gastos
    Route::get('/gastos',      [GastosController::class, 'index'])->name('gastos.index');
    Route::get('/gastos/list', [GastosController::class, 'list'])->name('gastos.list');

    // Pedidos (list before wildcard)
    Route::get('/pedidos',          [PedidosController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/list',     [PedidosController::class, 'list'])->name('pedidos.list');
    Route::get('/pedidos/{codigo}', [PedidosController::class, 'detalle'])->name('pedidos.detalle');

    // Albaranes
    Route::get('/albaranes/{codigo}', [AlbaranesController::class, 'detalle'])->name('albaranes.detalle');

    // Visitas comerciales
    Route::get('/visitas/{codigo}', [VisitasController::class, 'detalle'])->name('visitas.detalle');

    // Solicitudes de presupuesto
    Route::get('/solicitudes/{codigo}', [SolicitudesController::class, 'detalle'])->name('solicitudes.detalle');

    // Listados
    Route::get('/listados',                 [ListadosController::class, 'index'])->name('listados.index');
    Route::get('/listados/ventas-clientes', [ListadosController::class, 'ventasClientes'])->name('listados.ventas-clientes');
    Route::get('/listados/clientes',        [ListadosController::class, 'clientes'])->name('listados.clientes');

    // Objetivos
    Route::get('/objetivos', [ObjetivosController::class, 'index'])->name('objetivos.index');

    // Incidencias SAT
    Route::get('/incidencias',      [IncidenciasController::class, 'index'])->name('incidencias.index');
    Route::get('/incidencias/list', [IncidenciasController::class, 'list'])->name('incidencias.list');

    // RRHH
    Route::get('/rrhh', [RrhhController::class, 'index'])->name('rrhh.index');

    // Cliente — tabs AJAX
    Route::get('/clientes/{codigo}/contactos',            [ClientesController::class, 'tabContactos'])->name('clientes.tab.contactos');
    Route::get('/clientes/{codigo}/direcciones',          [ClientesController::class, 'tabDirecciones'])->name('clientes.tab.direcciones');
    Route::get('/clientes/{codigo}/visitas',              [ClientesController::class, 'tabVisitas'])->name('clientes.tab.visitas');
    Route::get('/clientes/{codigo}/incidencias',          [ClientesController::class, 'tabIncidencias'])->name('clientes.tab.incidencias');
    Route::get('/clientes/{codigo}/solicitudes',          [ClientesController::class, 'tabSolicitudes'])->name('clientes.tab.solicitudes');
    Route::get('/clientes/{codigo}/ventas-sgfa',          [ClientesController::class, 'tabVentasSgfa'])->name('clientes.tab.ventas-sgfa');
    Route::get('/clientes/{codigo}/articulos-vendidos',   [ClientesController::class, 'tabArticulosVendidos'])->name('clientes.tab.articulos-vendidos');
    Route::get('/clientes/{codigo}/llamadas',             [ClientesController::class, 'tabLlamadas'])->name('clientes.tab.llamadas');
    Route::get('/clientes/{codigo}/albaranes',            [ClientesController::class, 'tabAlbaranes'])->name('clientes.tab.albaranes');
    Route::get('/clientes/{codigo}/horarios',             [ClientesController::class, 'tabHorarios'])->name('clientes.tab.horarios');
});
