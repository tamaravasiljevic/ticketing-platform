<?php

    return [

        'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

        'allowed_methods' => ['*'],

        'allowed_origins' => [
            'http://127.0.0.1:5173',
            'https://unitalicized-unsadistic-lainey.ngrok-free.dev',
        ],

        'allowed_origins_patterns' => [],

        'allowed_headers' => ['*'],

        'exposed_headers' => false,

        'max_age' => false,

        'supports_credentials' => false,

    ];
