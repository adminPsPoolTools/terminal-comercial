<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL base de la API GesPlane
    |--------------------------------------------------------------------------
    */
    'api_url' => env('CRM_API_URL', 'http://pspool.dyndns.org:10445/ApiGesplanet_TC/public/api/'),

    /*
    |--------------------------------------------------------------------------
    | URL base de Recursos Humanos
    |--------------------------------------------------------------------------
    */
    'ws_rh' => env('CRM_WS_RH', 'http://pspool.dyndns.org:10445/Recursos_humanos_TC'),

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

];
