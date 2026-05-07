<?php

namespace App\Services;

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
        return $this->request('GET', $this->baseUrl . $endpoint, $params);
    }

    // ──────────────────────────────────────────────────────────────
    // Petición POST (JSON)
    // ──────────────────────────────────────────────────────────────
    public function post(string $endpoint, array $data = []): mixed
    {
        return $this->request('POST', $this->baseUrl . $endpoint, $data);
    }

    // ──────────────────────────────────────────────────────────────
    // Petición PUT (JSON)
    // ──────────────────────────────────────────────────────────────
    public function put(string $endpoint, array $data = []): mixed
    {
        return $this->request('PUT', $this->baseUrl . $endpoint, $data);
    }

    // ──────────────────────────────────────────────────────────────
    // Petición DELETE
    // ──────────────────────────────────────────────────────────────
    public function delete(string $endpoint, array $params = []): bool
    {
        return $this->request('DELETE', $this->baseUrl . $endpoint, $params) !== null;
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
        return $this->request('GET', $url, $params, "ApiService::getRH [{$path}]");
    }

    protected function request(string $method, string $url, array $data = [], ?string $logContext = null): mixed
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';

        try {
            if (! function_exists('curl_init')) {
                throw new \RuntimeException('PHP cURL extension is not available.');
            }

            $ch = curl_init();
            if ($ch === false) {
                throw new \RuntimeException('Unable to initialize cURL.');
            }

            $headers = ['Accept: application/json'];
            $method = strtoupper($method);

            if ($method === 'GET' && ! empty($data)) {
                $query = http_build_query($data);
                $url .= (str_contains($url, '?') ? '&' : '?') . $query;
            }

            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => $this->timeout,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_CUSTOMREQUEST => $method,
            ]);

            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true) && ! empty($data)) {
                $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($payload === false) {
                    throw new \RuntimeException('Unable to encode JSON payload.');
                }

                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            }

            $body = curl_exec($ch);
            if ($body === false) {
                $error = curl_error($ch) ?: 'Unknown cURL error';
                curl_close($ch);
                throw new \RuntimeException($error);
            }

            $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status < 200 || $status >= 300) {
                Log::warning($logContext . " HTTP {$status}", [
                    'url' => $url,
                    'body' => $this->truncateBody($body),
                ]);
                return null;
            }

            return $this->decodeResponse($body, $logContext, $url);
        } catch (\Throwable $e) {
            Log::error($logContext . ' ' . $e->getMessage(), ['url' => $url]);
            return null;
        }
    }

    protected function decodeResponse(string $body, string $logContext, string $url): mixed
    {
        $trimmed = trim($body);

        if ($trimmed === '') {
            return null;
        }

        $decoded = json_decode($body);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        Log::warning($logContext . ' Invalid JSON response', [
            'url' => $url,
            'body' => $this->truncateBody($body),
            'json_error' => json_last_error_msg(),
        ]);

        return null;
    }

    protected function extractEndpoint(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        return ltrim((string) basename($path), '/');
    }

    protected function truncateBody(string $body, int $limit = 500): string
    {
        if (strlen($body) <= $limit) {
            return $body;
        }

        return substr($body, 0, $limit) . '...';
    }
}
