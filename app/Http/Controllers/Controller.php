<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Normaliza una fecha introducida por el usuario al formato que espera la API.
     *
     * Acepta dd/mm/aaaa, dd-mm-aaaa, dd.mm.aaaa (formato español) y aaaa-mm-dd
     * (inputs type="date"). Devuelve la fecha en config('crm.api_date_format'),
     * por defecto aaaa-mm-dd, que Firebird interpreta siempre sin ambigüedad
     * (con dd/mm/aaaa Firebird lee mm/dd/aaaa y falla con días > 12).
     *
     * Si la fecha está vacía o no es válida se usa $default (también en formato
     * español o ISO); si $default es null se devuelve ''.
     */
    protected function fechaApi(?string $fecha, ?string $default = null): string
    {
        $parsed = $this->parseFecha($fecha) ?? $this->parseFecha($default);

        return $parsed ? $parsed->format(config('crm.api_date_format', 'Y-m-d')) : '';
    }

    private function parseFecha(?string $fecha): ?\DateTimeImmutable
    {
        $fecha = trim((string) $fecha);

        if ($fecha === '') {
            return null;
        }

        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $fecha, $m)) {
            [$y, $mo, $d] = [(int) $m[1], (int) $m[2], (int) $m[3]];
        } elseif (preg_match('/^(\d{1,2})[\/.\-](\d{1,2})[\/.\-](\d{2}|\d{4})$/', $fecha, $m)) {
            [$d, $mo, $y] = [(int) $m[1], (int) $m[2], (int) $m[3]];
            if ($y < 100) {
                $y += 2000;
            }
        } else {
            return null;
        }

        if (! checkdate($mo, $d, $y)) {
            return null;
        }

        return new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $y, $mo, $d));
    }

    /**
     * Comercial cuyos datos se consultan: el de la sesión, salvo que sea
     * administrador (config crm.admin_comerciales) y pida otro usuario.
     */
    protected function usuarioConsultado(mixed $solicitado): int
    {
        $comercial = (int) session('comercial_id');

        if ($solicitado !== null && $solicitado !== ''
            && in_array($comercial, config('crm.admin_comerciales', []))) {
            return (int) $solicitado;
        }

        return $comercial;
    }
}
