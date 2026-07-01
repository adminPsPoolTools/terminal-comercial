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
        $this->app->singleton(ApiService::class, function () {
            return new ApiService();
        });
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            $request = request();
            $rootUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');

            if ($rootUrl !== '') {
                URL::forceRootUrl($rootUrl);
            }

            if ($request->isSecure()) {
                URL::forceScheme('https');
            }
        }
    }
}
