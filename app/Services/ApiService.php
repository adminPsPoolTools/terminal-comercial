<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ApiService
{
    protected string $baseUrl;
    protected string $rhBaseUrl;
    protected int $timeout;
    protected int $connectTimeout;
    protected bool $verifySsl;
    protected string $payloadFormat;
    protected string $transport;
    protected string $userAgent;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('crm.api_url'), '/') . '/';
        $this->rhBaseUrl = rtrim((string) config('crm.ws_rh'), '/') . '/';
        $this->timeout = (int) config('crm.api_timeout', 30);
        $this->connectTimeout = (int) config('crm.api_connect_timeout', 10);
        $this->verifySsl = (bool) config('crm.api_verify_ssl', true);
        $this->payloadFormat = (string) config('crm.api_payload_format', 'form');
        $this->transport = (string) config('crm.api_transport', 'auto');
        $this->userAgent = (string) config('crm.api_user_agent', 'CRM Comercial Ps-pool');
    }

    public function get(string $endpoint, array $params = []): mixed
    {
        return $this->request('GET', $this->makeApiUrl($endpoint), $params);
    }

    public function post(string $endpoint, array $data = []): mixed
    {
        return $this->request('POST', $this->makeApiUrl($endpoint), $data);
    }

    public function put(string $endpoint, array $data = []): mixed
    {
        return $this->request('PUT', $this->makeApiUrl($endpoint), $data);
    }

    public function delete(string $endpoint, array $params = []): bool
    {
        return $this->requestSuccessful('DELETE', $this->makeApiUrl($endpoint), $params);
    }

    public function getArray(string $endpoint, array $params = []): array
    {
        return $this->normalizeList($this->get($endpoint, $params));
    }

    public function loginVendedor(string $comercial): ?object
    {
        return $this->getItem('loginVendedores', ['comercial' => $comercial]);
    }

    public function loginEmpleado(string $comercial): ?object
    {
        return $this->getItem('loginEmpleados', ['comercial' => $comercial]);
    }

    public function contarAlarmas(int $comercial, string $tipo): int
    {
        $row = $this->getItem('contarAlarmasRecordatorios', [
            'comercial' => $comercial,
            'tipo' => $tipo,
        ]);

        return (int) ($row->N_REG ?? 0);
    }

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

    public function obtenerPresupuestos(array $filtros): array
    {
        return $this->getArray('buscarPresupuestos', $filtros);
    }

    public function obtenerEstadosPresupuesto(): array
    {
        return $this->getArray('obtenerEstadosPresupuesto');
    }

    public function buscarExpedientes(array $filtros): array
    {
        return $this->getArray('buscarExpedientes', $filtros);
    }

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

    public function buscarPedidos(array $filtros): array
    {
        return $this->getArray('buscarPedidos', $filtros);
    }

    public function obtenerEstadosPedido(): array
    {
        return $this->getArray('obtenerEstadosPedido');
    }

    public function buscarClientes(array $filtros): array
    {
        return $this->getArray('buscarClientes', $filtros);
    }

    public function obtenerCliente(string $codigo): ?object
    {
        return $this->getItem('obtenerCliente', ['codigo' => $codigo]);
    }

    public function obtenerCategorias(): array
    {
        return $this->getArray('obtenerCategorias');
    }

    public function obtenerTipos(): array
    {
        return $this->getArray('obtenerTipos');
    }

    public function buscarArticulos(array $filtros): array
    {
        return $this->getArray('buscarArticulos', $filtros);
    }

    public function buscarIncidenciasSat(array $filtros): array
    {
        return $this->getArray('buscarIncidenciasSat', $filtros);
    }

    public function obtenerEstadosIncidencia(): array
    {
        return $this->getArray('obtenerSelectEstadoIncidencia');
    }

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

    public function getRH(string $path, array $params = []): mixed
    {
        return $this->request('GET', $this->makeRhUrl($path), $params, "ApiService::getRH [{$path}]");
    }

    protected function getItem(string $endpoint, array $params = []): ?object
    {
        return $this->normalizeItem($this->get($endpoint, $params));
    }

    protected function request(string $method, string $url, array $data = [], ?string $logContext = null): mixed
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';

        try {
            $response = $this->send($method, $url, $data);
        } catch (\Throwable $e) {
            Log::error($logContext . ' ' . $e->getMessage(), ['url' => $url]);
            return null;
        }

        if (! $this->isSuccessfulStatus($response['status'])) {
            Log::warning($logContext . " HTTP {$response['status']}", [
                'url' => $response['url'],
                'transport' => $response['transport'],
                'body' => $this->truncateBody($response['body']),
            ]);

            return null;
        }

        return $this->decodeResponse($response['body'], $logContext, $response['url']);
    }

    protected function requestSuccessful(string $method, string $url, array $data = [], ?string $logContext = null): bool
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';

        try {
            $response = $this->send($method, $url, $data);
        } catch (\Throwable $e) {
            Log::error($logContext . ' ' . $e->getMessage(), ['url' => $url]);
            return false;
        }

        if ($this->isSuccessfulStatus($response['status'])) {
            return true;
        }

        Log::warning($logContext . " HTTP {$response['status']}", [
            'url' => $response['url'],
            'transport' => $response['transport'],
            'body' => $this->truncateBody($response['body']),
        ]);

        return false;
    }

    protected function send(string $method, string $url, array $data = []): array
    {
        $prepared = $this->prepareRequest($method, $url, $data);

        if ($this->transport !== 'stream' && function_exists('curl_init')) {
            return $this->sendWithCurl($prepared);
        }

        if ($this->transport === 'curl' && ! function_exists('curl_init')) {
            Log::warning('ApiService transport set to curl but cURL extension is not available; falling back to PHP streams.');
        }

        return $this->sendWithStream($prepared);
    }

    protected function prepareRequest(string $method, string $url, array $data = []): array
    {
        $method = strtoupper($method);
        $data = $this->sanitizeData($data);
        $headers = [
            'Accept: application/json, text/plain, */*',
            'User-Agent: ' . $this->userAgent,
        ];
        $body = null;
        $bodyFormat = $this->resolveBodyFormat($method);

        if ($method === 'GET' || $bodyFormat === 'query') {
            $query = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
            if ($query !== '') {
                $url .= (str_contains($url, '?') ? '&' : '?') . $query;
            }
        } elseif ($bodyFormat === 'json') {
            $body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($body === false) {
                throw new \RuntimeException('Unable to encode JSON payload.');
            }

            $headers[] = 'Content-Type: application/json';
        } else {
            $body = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        return [
            'method' => $method,
            'url' => $url,
            'headers' => $headers,
            'body' => $body,
            'body_format' => $bodyFormat,
        ];
    }

    protected function sendWithCurl(array $request): array
    {
        $ch = curl_init();

        if ($ch === false) {
            throw new \RuntimeException('Unable to initialize cURL.');
        }

        $options = [
            CURLOPT_URL => $request['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $request['headers'],
            CURLOPT_CUSTOMREQUEST => $request['method'],
            CURLOPT_ENCODING => '',
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];

        if ($request['body'] !== null && $request['method'] !== 'GET') {
            $options[CURLOPT_POSTFIELDS] = $request['body'];
        }

        if (str_starts_with($request['url'], 'https://')) {
            $options[CURLOPT_SSL_VERIFYPEER] = $this->verifySsl;
            $options[CURLOPT_SSL_VERIFYHOST] = $this->verifySsl ? 2 : 0;
        }

        curl_setopt_array($ch, $options);

        $body = curl_exec($ch);

        if ($body === false) {
            $error = curl_error($ch) ?: 'Unknown cURL error';
            curl_close($ch);
            throw new \RuntimeException($error);
        }

        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $status,
            'body' => (string) $body,
            'url' => $request['url'],
            'transport' => 'curl',
        ];
    }

    protected function sendWithStream(array $request): array
    {
        $headers = implode("\r\n", $request['headers']);
        $context = stream_context_create([
            'http' => [
                'method' => $request['method'],
                'header' => $headers,
                'content' => $request['body'] ?? '',
                'timeout' => $this->timeout,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => $this->verifySsl,
                'verify_peer_name' => $this->verifySsl,
                'allow_self_signed' => ! $this->verifySsl,
            ],
        ]);

        $body = @file_get_contents($request['url'], false, $context);
        $responseHeaders = $http_response_header ?? [];
        $status = $this->extractStatusFromHeaders($responseHeaders);

        if ($body === false && $status === 0) {
            $error = error_get_last();
            throw new \RuntimeException($error['message'] ?? 'Unknown stream transport error.');
        }

        return [
            'status' => $status,
            'body' => $body === false ? '' : (string) $body,
            'url' => $request['url'],
            'transport' => 'stream',
        ];
    }

    protected function decodeResponse(string $body, string $logContext, string $url): mixed
    {
        $trimmed = trim($this->stripUtf8Bom($body));

        if ($trimmed === '') {
            return null;
        }

        $decoded = json_decode($trimmed);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        if (! str_starts_with($trimmed, '<')) {
            return $trimmed;
        }

        Log::warning($logContext . ' Invalid JSON response', [
            'url' => $url,
            'body' => $this->truncateBody($trimmed),
            'json_error' => json_last_error_msg(),
        ]);

        return null;
    }

    protected function normalizeList(mixed $result): array
    {
        $result = $this->unwrapPayload($result);

        if (is_array($result)) {
            return array_values(array_filter(
                array_map([$this, 'toObject'], $result),
                static fn ($item) => $item instanceof \stdClass
            ));
        }

        if (is_object($result)) {
            return [$result];
        }

        return [];
    }

    protected function normalizeItem(mixed $result): ?object
    {
        $result = $this->unwrapPayload($result);

        if (is_array($result)) {
            $first = reset($result);
            return $first === false ? null : $this->toObject($first);
        }

        if (is_object($result)) {
            return $result;
        }

        return null;
    }

    protected function unwrapPayload(mixed $result): mixed
    {
        $payloadKeys = ['data', 'datos', 'resultado', 'result', 'results', 'items', 'rows', 'row'];

        while (is_object($result)) {
            foreach ($payloadKeys as $key) {
                if (property_exists($result, $key)) {
                    $result = $result->{$key};
                    continue 2;
                }
            }

            return $result;
        }

        return $result;
    }

    protected function toObject(mixed $value): ?object
    {
        if (is_object($value)) {
            return $value;
        }

        if (is_array($value)) {
            return json_decode(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        return null;
    }

    protected function resolveBodyFormat(string $method): string
    {
        if (strtoupper($method) === 'GET') {
            return 'query';
        }

        return in_array($this->payloadFormat, ['json', 'query', 'form'], true)
            ? $this->payloadFormat
            : 'form';
    }

    protected function sanitizeData(array $data): array
    {
        return array_filter($data, static fn ($value) => $value !== null);
    }

    protected function makeApiUrl(string $endpoint): string
    {
        return $this->baseUrl . ltrim($endpoint, '/');
    }

    protected function makeRhUrl(string $path): string
    {
        return $this->rhBaseUrl . ltrim($path, '/');
    }

    protected function isSuccessfulStatus(int $status): bool
    {
        return $status >= 200 && $status < 300;
    }

    protected function extractStatusFromHeaders(array $headers): int
    {
        foreach ($headers as $header) {
            if (preg_match('/^HTTP\/\d+(?:\.\d+)?\s+(\d{3})/i', $header, $matches)) {
                return (int) $matches[1];
            }
        }

        return 0;
    }

    protected function extractEndpoint(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        return ltrim((string) basename($path), '/');
    }

    protected function stripUtf8Bom(string $value): string
    {
        return preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;
    }

    protected function truncateBody(string $body, int $limit = 500): string
    {
        if (strlen($body) <= $limit) {
            return $body;
        }

        return substr($body, 0, $limit) . '...';
    }
}
