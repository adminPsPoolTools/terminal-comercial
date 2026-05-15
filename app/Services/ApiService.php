<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ApiService
{
    protected array $apiBaseUrls;
    protected array $rhBaseUrls;
    protected int $timeout;
    protected int $connectTimeout;
    protected bool $verifySsl;
    protected string $payloadFormat;
    protected string $transport;
    protected string $userAgent;
    protected bool $forceIpv4;
    protected bool $readFallbackPost;

    public function __construct()
    {
        $this->apiBaseUrls = $this->resolveBaseUrls('api_url', 'api_fallback_urls');
        $this->rhBaseUrls = $this->resolveBaseUrls('ws_rh', 'ws_rh_fallback_urls');
        $this->timeout = (int) config('crm.api_timeout', 30);
        $this->connectTimeout = (int) config('crm.api_connect_timeout', 10);
        $this->verifySsl = (bool) config('crm.api_verify_ssl', true);
        $this->payloadFormat = (string) config('crm.api_payload_format', 'form');
        $this->transport = (string) config('crm.api_transport', 'auto');
        $this->userAgent = (string) config('crm.api_user_agent', 'CRM Comercial Ps-pool');
        $this->forceIpv4 = (bool) config('crm.api_force_ipv4', false);
        $this->readFallbackPost = (bool) config('crm.api_read_fallback_post', true);
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
        return $this->getArray('obtenerPresupuestosFichaCliente', $filtros);
    }

    public function obtenerEstadosPresupuesto(): array
    {
        return $this->getArray('obtenerSelectEstadosPresupuesto');
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
        return $this->getArray('obtenerPedidosFichaCliente', $filtros);
    }

    public function obtenerEstadosPedido(): array
    {
        return $this->getArray('obtenerSelectEstadosPedido');
    }

    public function buscarClientes(array $filtros): array
    {
        return $this->getArray('obtenerListadoClientes', $filtros);
    }

    public function obtenerCliente(string $codigo): ?object
    {
        return $this->getItem('obtenerDetalleFichaCliente', ['cliente' => $codigo]);
    }

    public function obtenerCategorias(): array
    {
        return $this->getArray('obtenerCategoriasCliente');
    }

    public function obtenerTipos(): array
    {
        return $this->getArray('obtenerTiposCliente');
    }

    public function buscarArticulos(array $filtros): array
    {
        return $this->getArray('buscarArticulos', $filtros);
    }

    public function buscarIncidenciasSat(array $filtros): array
    {
        return $this->getArray('buscarIncidencias', $filtros);
    }

    public function obtenerContactosCliente(string $codigo, int $comercial): array
    {
        return $this->getArray('obtenerContactosFichaCliente', ['cliente' => $codigo, 'comercial' => $comercial]);
    }

    public function obtenerDireccionesCliente(string $codigo): array
    {
        return $this->getArray('obtenerDireccionesCliente', ['cliente' => $codigo]);
    }

    public function obtenerVisitasCliente(string $codigo, int $comercial): array
    {
        return $this->getArray('obtenerVisitasFichaCliente', ['cliente' => $codigo, 'comercial' => $comercial]);
    }

    public function obtenerIncidenciasCliente(string $codigo): array
    {
        return $this->getArray('obtenerIncidenciasPorCliente', ['cliente' => $codigo]);
    }

    public function obtenerSolicitudesPresupuestoCliente(string $codigo, string $filtro = ''): array
    {
        return $this->getArray('obtenerSolicitudesPresupuestosFichaCliente', ['cliente' => $codigo, 'filtro' => $filtro]);
    }

    public function obtenerVentasSgfa(string $fechaDesde, string $codigo): array
    {
        return $this->getArray('obtenerVentasSgfa', ['fecha_desde' => $fechaDesde, 'cliente' => $codigo, 'proyecto' => 1]);
    }

    public function obtenerArticulosVendidos(string $fechaDesde, string $codigo): array
    {
        return $this->getArray('obtenerArticulosVendidos', ['fecha_desde' => $fechaDesde, 'cliente' => $codigo, 'proyecto' => 1]);
    }

    public function obtenerLlamadasCliente(string $codigo): array
    {
        return $this->getArray('obtenerLlamadasFichaClientes', ['cliente' => $codigo]);
    }

    public function obtenerAlbaranesCliente(string $fechaDesde, int $comercial, string $codigo): array
    {
        return $this->getArray('obtenerAlbaranesFichaClientes', [
            'fecha_desde' => $fechaDesde,
            'comercial'   => $comercial,
            'cliente'     => $codigo,
            'titulo'      => '',
        ]);
    }

    public function obtenerHorariosCliente(string $codigo): array
    {
        return $this->getArray('obtenerHorariosCliente', ['cliente' => $codigo]);
    }

    public function obtenerDetallePresupuesto(string $codigo): ?object
    {
        return $this->getItem('obtenerInformacionPresupuesto', ['presupuesto' => $codigo]);
    }

    public function obtenerLineasPresupuesto(string $codigo): array
    {
        return $this->getArray('obtenerArticulosPresupuesto', ['presupuesto' => $codigo]);
    }

    public function obtenerDetallePedido(string $codigo): ?object
    {
        return $this->getItem('obtenerInformacionInformePedido', ['proyecto' => config('crm.proyecto', 1), 'pedido' => $codigo]);
    }

    public function obtenerLineasPedido(string $codigo): array
    {
        return $this->getArray('obtenerLineasPedidoInforme', ['proyecto' => config('crm.proyecto', 1), 'pedido' => $codigo]);
    }

    public function obtenerDetalleAlbaran(string $codigo): ?object
    {
        return $this->getItem('obtenerInformacionAlbaran', ['proyecto' => config('crm.proyecto', 1), 'albaran' => $codigo]);
    }

    public function obtenerLineasAlbaran(string $codigo): array
    {
        return $this->getArray('obtenerArticulosAlbaran', ['albaran' => $codigo]);
    }

    public function obtenerMotivosVisitas(): array
    {
        return $this->getArray('obtenerMotivosVisitas');
    }

    public function obtenerListaAccionesVisitas(): array
    {
        return $this->getArray('obtenerAccionesVisitas');
    }

    public function obtenerListaAsuntosVisitas(): array
    {
        return $this->getArray('obtenerAsuntosAccionesVisitas');
    }

    public function obtenerNuevoCodigoVisita(): ?object
    {
        return $this->getItem('obtenerNuevoCodigoVisita');
    }

    public function crearVisitaComercial(array $datos): ?object
    {
        return $this->normalizeItem($this->post('crearVisitaComercial', $datos));
    }

    public function asignarAccionesVisita(string $codigo, string $acciones): bool
    {
        $result = $this->post('asignarAccionesVisita', ['codigo' => $codigo, 'acciones' => $acciones]);
        return $result !== null;
    }

    public function asignarAsuntosVisita(string $codigo, string $asuntos): bool
    {
        $result = $this->post('asignarAsuntosVisita', ['codigo' => $codigo, 'asuntos' => $asuntos]);
        return $result !== null;
    }

    public function obtenerDetalleVisita(string $codigo): ?object
    {
        return $this->getItem('obtenerDetalleVisita', ['codigo' => $codigo]);
    }

    public function obtenerAccionesVisita(string $codigo): array
    {
        return $this->getArray('obtenerAccionesVisitaPorCodigo', ['codigo' => $codigo]);
    }

    public function obtenerAsuntosVisita(string $codigo): array
    {
        return $this->getArray('obtenerAsuntosVisitaPorCodigo', ['codigo' => $codigo]);
    }

    public function obtenerDetalleSolicitud(string $codigo): ?object
    {
        return $this->getItem('obtenerSolicitudPresupuestoPorCodigo', ['codigo' => $codigo, 'visita_comercial' => '']);
    }

    public function crearCliente(array $datos): ?object
    {
        return $this->normalizeItem($this->post('crearActualizarCliente', $datos));
    }

    public function obtenerEstadoActualPresupuesto(string $codigo): ?object
    {
        return $this->getItem('cargarDetalleEstadoPresupuesto', ['codigo' => $codigo]);
    }

    public function actualizarPresupuesto(string $codigo, string $estado, string $comentario): bool
    {
        $result = $this->post('actualizarEstadoPresupuesto', [
            'presupuesto' => $codigo,
            'estado'      => $estado,
            'comentario'  => $comentario,
        ]);
        return $result !== null;
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

    public function probeEndpoint(string $endpoint, array $params = [], bool $rh = false, string $method = 'GET'): array
    {
        $url = $rh ? $this->makeRhUrl($endpoint) : $this->makeApiUrl($endpoint);
        $result = $this->performRequest(strtoupper($method), $url, $params, false);

        return [
            'ok' => $result['ok'],
            'final_method' => $result['method'],
            'final_url' => $result['url'],
            'final_transport' => $result['transport'],
            'status' => $result['status'],
            'error' => $result['error'],
            'body_preview' => $this->truncateBody($result['body'] ?? ''),
            'attempts' => $result['attempts'],
        ];
    }

    protected function getItem(string $endpoint, array $params = []): ?object
    {
        return $this->normalizeItem($this->get($endpoint, $params));
    }

    protected function request(string $method, string $url, array $data = [], ?string $logContext = null): mixed
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';
        $result = $this->performRequest($method, $url, $data, true, $logContext);

        return $result['ok'] ? $result['decoded'] : null;
    }

    protected function requestSuccessful(string $method, string $url, array $data = [], ?string $logContext = null): bool
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';
        $result = $this->performRequest($method, $url, $data, true, $logContext);

        return $result['ok'];
    }

    protected function performRequest(string $method, string $url, array $data = [], bool $logFailures = true, ?string $logContext = null): array
    {
        $logContext ??= 'ApiService::' . strtolower($method) . ' [' . $this->extractEndpoint($url) . ']';
        $attempts = [];
        $lastFailure = [
            'ok' => false,
            'status' => 0,
            'body' => '',
            'url' => $url,
            'transport' => null,
            'method' => strtoupper($method),
            'decoded' => null,
            'error' => null,
            'attempts' => [],
        ];

        foreach ($this->candidateMethods($method) as $candidateMethod) {
            foreach ($this->candidateUrls($url) as $candidateUrl) {
                foreach ($this->candidateTransports() as $transport) {
                    $prepared = $this->prepareRequest($candidateMethod, $candidateUrl, $data);

                    try {
                        $response = $this->sendPrepared($prepared, $transport);
                    } catch (\Throwable $e) {
                        $attempts[] = [
                            'method' => $candidateMethod,
                            'url' => $prepared['url'],
                            'transport' => $transport,
                            'status' => 0,
                            'error' => $e->getMessage(),
                        ];
                        $lastFailure = [
                            'ok' => false,
                            'status' => 0,
                            'body' => '',
                            'url' => $prepared['url'],
                            'transport' => $transport,
                            'method' => $candidateMethod,
                            'decoded' => null,
                            'error' => $e->getMessage(),
                            'attempts' => $attempts,
                        ];
                        continue;
                    }

                    $attempts[] = [
                        'method' => $candidateMethod,
                        'url' => $response['url'],
                        'transport' => $response['transport'],
                        'status' => $response['status'],
                        'error' => null,
                    ];

                    if (! $this->isSuccessfulStatus($response['status'])) {
                        $lastFailure = [
                            'ok' => false,
                            'status' => $response['status'],
                            'body' => $response['body'],
                            'url' => $response['url'],
                            'transport' => $response['transport'],
                            'method' => $candidateMethod,
                            'decoded' => null,
                            'error' => null,
                            'attempts' => $attempts,
                        ];
                        continue;
                    }

                    $decoded = $this->decodeResponse($response['body'], $logContext, $response['url'], $logFailures);

                    return [
                        'ok' => true,
                        'status' => $response['status'],
                        'body' => $response['body'],
                        'url' => $response['url'],
                        'transport' => $response['transport'],
                        'method' => $candidateMethod,
                        'decoded' => $decoded,
                        'error' => null,
                        'attempts' => $attempts,
                    ];
                }
            }
        }

        if ($logFailures) {
            $this->logFailure($logContext, $lastFailure);
        }

        return $lastFailure;
    }

    protected function sendPrepared(array $request, string $transport): array
    {
        if ($transport === 'curl') {
            if (! function_exists('curl_init')) {
                throw new \RuntimeException('PHP cURL extension is not available.');
            }

            return $this->sendWithCurl($request);
        }

        return $this->sendWithStream($request);
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

        if ($this->forceIpv4 && defined('CURL_IPRESOLVE_V4')) {
            $options[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }

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

    protected function decodeResponse(string $body, string $logContext, string $url, bool $logFailures): mixed
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

        if ($logFailures) {
            Log::warning($logContext . ' Invalid JSON response', [
                'url' => $url,
                'body' => $this->truncateBody($trimmed),
                'json_error' => json_last_error_msg(),
            ]);
        }

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

    protected function candidateMethods(string $method): array
    {
        $method = strtoupper($method);

        if ($method === 'GET' && $this->readFallbackPost) {
            return ['GET', 'POST'];
        }

        return [$method];
    }

    protected function candidateTransports(): array
    {
        if ($this->transport === 'curl') {
            return ['curl'];
        }

        if ($this->transport === 'stream') {
            return ['stream'];
        }

        return function_exists('curl_init') ? ['curl', 'stream'] : ['stream'];
    }

    protected function candidateUrls(string $url): array
    {
        $candidates = [$url];

        $apiPrimary = $this->apiBaseUrls[0] ?? null;
        if ($apiPrimary && str_starts_with($url, $apiPrimary)) {
            $suffix = ltrim(substr($url, strlen($apiPrimary)), '/');

            foreach ($this->apiBaseUrls as $baseUrl) {
                $candidates[] = rtrim($baseUrl, '/') . '/' . $suffix;
            }
        }

        $rhPrimary = $this->rhBaseUrls[0] ?? null;
        if ($rhPrimary && str_starts_with($url, $rhPrimary)) {
            $suffix = ltrim(substr($url, strlen($rhPrimary)), '/');

            foreach ($this->rhBaseUrls as $baseUrl) {
                $candidates[] = rtrim($baseUrl, '/') . '/' . $suffix;
            }
        }

        return array_values(array_unique($candidates));
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
        return ($this->apiBaseUrls[0] ?? '') . ltrim($endpoint, '/');
    }

    protected function makeRhUrl(string $path): string
    {
        return ($this->rhBaseUrls[0] ?? '') . ltrim($path, '/');
    }

    protected function normalizeBaseUrls(string $primary, mixed $fallbacks): array
    {
        $urls = [];

        foreach (array_merge([$primary], $this->toUrlList($fallbacks)) as $url) {
            $url = trim((string) $url);

            if ($url === '') {
                continue;
            }

            $urls[] = rtrim($url, '/') . '/';
        }

        return array_values(array_unique($urls));
    }

    protected function resolveBaseUrls(string $primaryKey, string $fallbackKey): array
    {
        $suffix = $this->environmentSuffix();
        $primary = (string) (config("crm.{$primaryKey}_{$suffix}") ?: config("crm.{$primaryKey}"));
        $fallbacks = config("crm.{$fallbackKey}_{$suffix}");

        if ($fallbacks === null || $fallbacks === '') {
            $fallbacks = config("crm.{$fallbackKey}", []);
        }

        return $this->normalizeBaseUrls($primary, $fallbacks);
    }

    protected function environmentSuffix(): string
    {
        return app()->environment('local') ? 'local' : 'server';
    }

    protected function toUrlList(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $value)));
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

    protected function logFailure(string $logContext, array $result): void
    {
        $context = [
            'url' => $result['url'],
            'transport' => $result['transport'],
            'method' => $result['method'],
            'attempts' => $result['attempts'],
        ];

        if ($result['error']) {
            Log::error($logContext . ' ' . $result['error'], $context);
            return;
        }

        Log::warning($logContext . " HTTP {$result['status']}", $context + [
            'body' => $this->truncateBody($result['body'] ?? ''),
        ]);
    }
}
