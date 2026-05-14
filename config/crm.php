<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL base de la API GesPlane
    |--------------------------------------------------------------------------
    */
    'api_url' => env('CRM_API_URL', 'http://pspool.dyndns.org:10445/ApiGesplanet_TC/public/api/'),
    'api_url_local' => env('CRM_API_URL_LOCAL'),
    'api_url_server' => env('CRM_API_URL_SERVER'),
    'api_fallback_urls' => env('CRM_API_FALLBACK_URLS', ''),
    'api_fallback_urls_local' => env('CRM_API_FALLBACK_URLS_LOCAL', ''),
    'api_fallback_urls_server' => env('CRM_API_FALLBACK_URLS_SERVER', ''),

    /*
    |--------------------------------------------------------------------------
    | URL base de Recursos Humanos
    |--------------------------------------------------------------------------
    */
    'ws_rh' => env('CRM_WS_RH', 'http://pspool.dyndns.org:10445/Recursos_humanos_TC'),
    'ws_rh_local' => env('CRM_WS_RH_LOCAL'),
    'ws_rh_server' => env('CRM_WS_RH_SERVER'),
    'ws_rh_fallback_urls' => env('CRM_WS_RH_FALLBACK_URLS', ''),
    'ws_rh_fallback_urls_local' => env('CRM_WS_RH_FALLBACK_URLS_LOCAL', ''),
    'ws_rh_fallback_urls_server' => env('CRM_WS_RH_FALLBACK_URLS_SERVER', ''),

    /*
    |--------------------------------------------------------------------------
    | Código del comercial técnico fijo
    |--------------------------------------------------------------------------
    */
    'comercial_tecnico' => (int) env('CRM_COMERCIAL_TECNICO', 102),

    /*
    |--------------------------------------------------------------------------
    | Timeout en segundos para las llamadas a la API
    |--------------------------------------------------------------------------
    */
    'api_timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Timeout de conexión inicial
    |--------------------------------------------------------------------------
    */
    'api_connect_timeout' => (int) env('CRM_API_CONNECT_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | Transporte HTTP: auto, curl o stream
    |--------------------------------------------------------------------------
    */
    'api_transport' => env('CRM_API_TRANSPORT', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Formato del cuerpo para POST/PUT/DELETE: form, json o query
    |--------------------------------------------------------------------------
    */
    'api_payload_format' => env('CRM_API_PAYLOAD_FORMAT', 'form'),

    /*
    |--------------------------------------------------------------------------
    | Verificación SSL para endpoints HTTPS
    |--------------------------------------------------------------------------
    */
    'api_verify_ssl' => filter_var(env('CRM_API_VERIFY_SSL', true), FILTER_VALIDATE_BOOL),

    /*
    |--------------------------------------------------------------------------
    | User-Agent enviado a la API
    |--------------------------------------------------------------------------
    */
    'api_user_agent' => env('CRM_API_USER_AGENT', 'CRM Comercial Ps-pool'),

    /*
    |--------------------------------------------------------------------------
    | Forzar IPv4 en cURL
    |--------------------------------------------------------------------------
    */
    'api_force_ipv4' => filter_var(env('CRM_API_FORCE_IPV4', false), FILTER_VALIDATE_BOOL),

    /*
    |--------------------------------------------------------------------------
    | Reintentar lecturas GET como POST x-www-form-urlencoded
    |--------------------------------------------------------------------------
    */
    'api_read_fallback_post' => filter_var(env('CRM_API_READ_FALLBACK_POST', true), FILTER_VALIDATE_BOOL),

    /*
    |--------------------------------------------------------------------------
    | Carpeta e imágenes
    |--------------------------------------------------------------------------
    */
    'images_folder' => env('IMAGES_FOLDER', 'storage/app/public/imagenes/'),
    'images_url'    => env('IMAGES_URL', '/storage/imagenes/'),

    /*
    |--------------------------------------------------------------------------
    | Dachser
    |--------------------------------------------------------------------------
    */
    'dachser_api_url' => env('DACHSER_API_URL', 'https://comercial.ps-pool.com/dachser_integracion/obtenerURLSeguimiento.php'),
    'dachser_codigo'  => env('DACHSER_CODIGO', '5'),

    /*
    |--------------------------------------------------------------------------
    | Comerciales con acceso a TODOS los usuarios en agenda/listados
    |--------------------------------------------------------------------------
    */
    'admin_comerciales' => [18, 9, 31],

    'proyecto' => (int) env('CRM_PROYECTO', 1),

];
