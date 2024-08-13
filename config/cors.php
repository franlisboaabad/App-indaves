<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */
    'paths' => ['api/*'], // Rutas a las que se aplicará CORS, por ejemplo, 'api/*' aplica a todas las rutas en api.php
    'allowed_methods' => ['*'], // Métodos HTTP permitidos, por ejemplo, ['GET', 'POST']
    'allowed_origins' => ['*'], // Orígenes permitidos, por ejemplo, ['http://localhost:3000']
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Cabeceras permitidas, por ejemplo, ['Content-Type', 'X-Requested-With']
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,

];
