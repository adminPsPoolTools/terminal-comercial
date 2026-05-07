<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected string $baseUrl;
    protected int    $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('crm.api_url'), '/') . '/';
        $this->timeout = config('crm.api_timeout', 30);
    }

    // ──────────────────────────────────────────────────────────────
    // Petición GET
    // ──────────────────────────────────────────────────────────────
    public function get(string $endpoint, array $params = []): mixed
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                return $response->object();   // stdClass / array de stdClass
            }

            Log::warning("ApiService::get [{$endpoint}] HTTP {$response->status()}");
            return null;
        } catch (\Throwable $e) {
            Log::error("ApiService::get [{$endpoint}] " . $e->getMessage());
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Petición POST (JSON)
    // ──────────────────────────────────────────────────────────────
    public function post(string $endpoint, array $data = []): mixed
    {
        try {
            $response = Http::timeout($this->timeout)
                ->asJson()
                ->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->object();
            }

            Log::warning("ApiService::post [{$endpoint}] HTTP {$response->status()}");
            return null;
        } catch (\Throwable $e) {
            Log::error("ApiService::post [{$endpoint}] " . $e->getMessage());
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Petición PUT (JSON)
    // ──────────────────────────────────────────────────────────────
    public function put(string $endpoint, array $data = []): mixed
    {
        try {
            $response = Http::timeout($this->timeout)
                ->asJson()
                ->put($this->baseUrl . $endpoint, $data);

            return $response->successful() ? $response->object() : null;
        } catch (\Throwable $e) {
            Log::error("ApiService::put [{$endpoint}] " . $e->getMessage());
            return null;
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Petición DELETE
    // ──────────────────────────────────────────────────────────────
    public function delete(string $endpoint, array $params = []): bool
    {
        try {
            $response = Http::timeout($this->timeout)
                ->delete($this->baseUrl . $endpoint, $params);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("ApiService::delete [{$endpoint}] " . $e->getMessage());
            return false;
        }
    }

    // ──────────────────────────────────────────────────────────────
    // Helper: devuelve array vacío en lugar de null cuando falla
    // ──────────────────────────────────────────────────────────────
    public function getArray(string $endpoint, array $params = []): array
    {
        $result = $this->get($endpoint, $params);
        if (is_array($result)) return $result;
        if (is_object($result)) return [$result];
        return [];
    }

    // ──────────────────────────────────────────────────────────────
    // Autenticación vendedores
    // ──────────────────────────────────────────────────────────────
    public function loginVendedor(string $comercial): mixed
    {
        return $this->get('loginVendedores', ['comercial' => $comercial]);
    }

    public function loginEmpleado(string $comercial): mixed
    {
        return $this->get('loginEmpleados', ['comercial' => $comercial]);
    }

    // ──────────────────────────────────────────────────────────────
    // Alarmas / recordatorios
    // ──────────────────────────────────────────────────────────────
    public function contarAlarmas(int $comercial, string $tipo): int
    {
        $row = $this->get('contarAlarmasRecordatorios', [
            'comercial' => $comercial,
            'tipo'      => $tipo,
        ]);
        return (int) ($row->N_REG ?? 0);
    }

    // ──────────────────────────────────────────────────────────────
    // Agenda
    // ──────────────────────────────────────────────────────────────
    public function buscarAgendas(array $filtros): array
    {
        return $this->getArray('buscarAgendas', $filtros);
    }

    public function obtenerEstadosExpediente(): array
    {
        return $this->getArray('obtenerEstadosExpediente');
    }

    public function obtenerUsuariosVendedores(): array
    {
        return $this->getArray('obtenerUsuariosVendedores');
    }

    // ──────────────────────────────────────────────────────────────
    // Presupuestos
    // ──────────────────────────────────────────────────────────────
    public function obtenerPresupuestos(array $filtros): array
    {
        return $this->getArray('buscarPresupuestos', $filtros);
    }

    public function obtenerEstadosPresupuesto(): array
    {
        return $this->getArray('obtenerEstadosPresupuesto');
    }

    // ──────────────────────────────────────────────────────────────
    // Expedientes
    // ──────────────────────────────────────────────────────────────
    public function buscarExpedientes(array $filtros): array
    {
        return $this->getArray('buscarExpedientes', $filtros);
    }

    // ──────────────────────────────────────────────────────────────
    // Gastos
    // ──────────────────────────────────────────────────────────────
    public function obtenerGastos(array $filtros): array
    {
        return $this->getArray('buscarGastos', $filtros);
    }

    public function obtenerTiposGasto(): array
    {
        return $this->getArray('obtenerTiposGasto');
    }

    public function obtenerMediosCobro(): array
    {
        return $this->getArray('obtenerTiposMediosCobro');
    }

    // ──────────────────────────────────────────────────────────────
    // Pedidos
    // ──────────────────────────────────────────────────────────────
    public function buscarPedidos(array $filtros): array
    {
        return $this->getArray('buscarPedidos', $filtros);
    }

    public function obtenerEstadosPedido(): array
    {
        return $this->getArray('obtenerEstadosPedido');
    }

    // ──────────────────────────────────────────────────────────────
    // Clientes
    // ──────────────────────────────────────────────────────────────
    public function buscarClientes(array $filtros): array
    {
        return $this->getArray('buscarClientes', $filtros);
    }

    public function obtenerCliente(string $codigo): mixed
    {
        return $this->get('obtenerCliente', ['codigo' => $codigo]);
    }

    public function obtenerCategorias(): array
    {
        return $this->getArray('obtenerCategorias');
    }

    public function obtenerTipos(): array
    {
        return $this->getArray('obtenerTipos');
    }

    // ──────────────────────────────────────────────────────────────
    // Artículos
    // ──────────────────────────────────────────────────────────────
    public function buscarArticulos(array $filtros): array
    {
        return $this->getArray('buscarArticulos', $filtros);
    }

    // ──────────────────────────────────────────────────────────────
    // Incidencias SAT
    // ──────────────────────────────────────────────────────────────
    public function buscarIncidenciasSat(array $filtros): array
    {
        return $this->getArray('buscarIncidenciasSat', $filtros);
    }

    public function obtenerEstadosIncidencia(): array
    {
        return $this->getArray('obtenerSelectEstadoIncidencia');
    }

    // ──────────────────────────────────────────────────────────────
    // Selectores genéricos
    // ──────────────────────────────────────────────────────────────
    public function obtenerProvincias(): array
    {
        return $this->getArray('obtenerProvincias');
    }

    public function obtenerPoblaciones(string $provincia): array
    {
        return $this->getArray('obtenerPoblaciones', ['provincia' => $provincia]);
    }

    public function obtenerVendedores(): array
    {
        return $this->getArray('obtenerVendedoresTerminalComercial');
    }

    // ──────────────────────────────────────────────────────────────
    // Listados / informes
    // ──────────────────────────────────────────────────────────────
    public function listadoVentasClientes(array $filtros): array
    {
        return $this->getArray('obtenerListadoVentasClientes', $filtros);
    }

    public function listadoClientes(array $filtros): array
    {
        return $this->getArray('obtenerListadoClientes', $filtros);
    }

    public function listadoObjetivos(array $filtros): array
    {
        return $this->getArray('obtenerListadoObjetivos', $filtros);
    }

    // ──────────────────────────────────────────────────────────────
    // Recursos Humanos (URL distinta)
    // ──────────────────────────────────────────────────────────────
    public function getRH(string $path, array $params = []): mixed
    {
        $url = rtrim(config('crm.ws_rh'), '/') . '/' . $path;
        try {
            $response = Http::timeout($this->timeout)->get($url, $params);
            return $response->successful() ? $response->object() : null;
        } catch (\Throwable $e) {
            Log::error("ApiService::getRH [{$path}] " . $e->getMessage());
            return null;
        }
    }
}
