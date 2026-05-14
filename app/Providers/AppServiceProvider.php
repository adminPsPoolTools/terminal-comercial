<?php

namespace App\Providers;

use App\Services\ApiService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar ApiService como singleton para reutilizar la instancia
        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService();
        });
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            $request = request();

            // Usamos solo scheme+host como root; el prefijo de path lo gestiona routes/web.php
            URL::forceRootUrl($request->getSchemeAndHttpHost());

            if ($request->isSecure()) {
                URL::forceScheme('https');
            }
        }
    }
}
