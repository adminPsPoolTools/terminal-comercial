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
