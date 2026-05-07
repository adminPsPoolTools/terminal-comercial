<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use Illuminate\Console\Command;

class ProbeApiCommand extends Command
{
    protected $signature = 'crm:probe-api
        {endpoint : Endpoint sin la base URL, por ejemplo loginEmpleados}
        {--param=* : Parámetros en formato clave=valor}
        {--method=GET : Método HTTP inicial}
        {--rh : Usa la base URL de RRHH}';

    protected $description = 'Prueba un endpoint de la API CRM desde el servidor actual.';

    public function handle(ApiService $api): int
    {
        $params = [];

        foreach ((array) $this->option('param') as $pair) {
            if (! str_contains($pair, '=')) {
                $this->warn("Parámetro ignorado: {$pair}");
                continue;
            }

            [$key, $value] = explode('=', $pair, 2);
            $params[$key] = $value;
        }

        $result = $api->probeEndpoint(
            (string) $this->argument('endpoint'),
            $params,
            (bool) $this->option('rh'),
            (string) $this->option('method')
        );

        $this->line('OK: ' . ($result['ok'] ? 'yes' : 'no'));
        $this->line('Method: ' . ($result['final_method'] ?? 'n/a'));
        $this->line('URL: ' . ($result['final_url'] ?? 'n/a'));
        $this->line('Transport: ' . ($result['final_transport'] ?? 'n/a'));
        $this->line('Status: ' . ($result['status'] ?? 0));

        if (! empty($result['error'])) {
            $this->line('Error: ' . $result['error']);
        }

        if (! empty($result['body_preview'])) {
            $this->line('Body: ' . $result['body_preview']);
        }

        $this->newLine();
        $this->line('Attempts:');

        foreach ($result['attempts'] as $attempt) {
            $summary = sprintf(
                '- %s %s [%s] status=%s',
                $attempt['method'] ?? 'GET',
                $attempt['url'] ?? 'n/a',
                $attempt['transport'] ?? 'n/a',
                $attempt['status'] ?? 0
            );

            if (! empty($attempt['error'])) {
                $summary .= ' error=' . $attempt['error'];
            }

            $this->line($summary);
        }

        return $result['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
