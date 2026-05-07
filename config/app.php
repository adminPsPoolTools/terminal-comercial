<?php

return [

    'name' => env('APP_NAME', 'CRM Comercial Ps-pool'),
    'env'  => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url'  => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'Europe/Madrid',
    'locale' => 'es',
    'fallback_locale' => 'es',
    'faker_locale' => 'es_ES',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',

    'maintenance' => ['driver' => 'file'],

    'providers' => Illuminate\Support\ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'aliases' => Illuminate\Support\Facades\Facade::defaultAliases()->merge([
        //
    ])->toArray(),

];
