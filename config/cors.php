<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Local dev
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:3000',
        'http://catering-family-jakarta-vue.test',
        'http://catering-family-jakarta-vue.test:5173',
        'http://localhost',
        'http://127.0.0.1',
        // Production — update setelah dapat URL Vercel
        env('FRONTEND_URL', ''),
    ],

    'allowed_origins_patterns' => [
        // Local
        '#^http://.*\.test$#',
        '#^http://localhost(:\d+)?$#',
        '#^http://127\.0\.0\.1(:\d+)?$#',
        // Vercel preview deployments
        '#^https://.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => false,
];
