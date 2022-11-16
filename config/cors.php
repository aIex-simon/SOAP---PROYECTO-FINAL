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

    'paths' => ['api/*', 'csrf-cookie'],

    'allowed_methods' => [
        'GET',
        'POST',
        'OPTIONS',
        'PATCH',
        'PUT',
        'DELETE'
    ],

    'allowed_origins' => [
        'https://www.tumisoft.cloud',
        'https://alpha.tumi-soft.net',
        'http://www.gotumi.com',
        'https://www.gotumi.com',
        'http://localhost',
        'http://localhost:19006',
        'http://192.168.100.39:19006',
        'http://192.168.3.159:19006',
        'http://172.0.1.160:19006',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'Origin',
        'Access-Control-Request-Headers',
        'Access-Control-Request-Method',
        'Cache-Control',
        'X-localization',
        'Accept'
    ],

    'exposed_headers' => [
        'Accept',
        'Content-Type',
        'Authorization',
        'Origin',
        'Access-Control-Request-Headers',
        'Access-Control-Request-Method',
        'Cache-Control',
        'X-localization'
    ],

    'max_age' => 6000,

    'supports_credentials' => false,
];
