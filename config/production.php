<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains production-specific configurations for the Odys
    | Rental Management application.
    |
    */

    'app' => [
        'name' => env('APP_NAME', 'Odys Rental Management'),
        'env' => 'production',
        'debug' => false,
        'url' => env('APP_URL', 'https://odys.ma'),
        'timezone' => 'UTC',
        'locale' => 'fr',
        'fallback_locale' => 'fr',
    ],

    'database' => [
        'default' => 'mysql',
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'odys_rental'),
                'username' => env('DB_USERNAME', 'odys_user'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
                'options' => [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ],
            ],
        ],
    ],

    'cache' => [
        'default' => 'file',
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('framework/cache/data'),
            ],
        ],
    ],

    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'encrypt' => false,
        'files' => storage_path('framework/sessions'),
        'connection' => null,
        'table' => 'sessions',
        'store' => null,
        'lottery' => [2, 100],
        'cookie' => 'odys_session',
        'path' => '/',
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    'logging' => [
        'default' => 'stack',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => ['single'],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => storage_path('logs/laravel.log'),
                'level' => 'error',
                'replace_placeholders' => true,
            ],
        ],
    ],

    'mail' => [
        'default' => 'smtp',
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
                'port' => env('MAIL_PORT', 587),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ],
        ],
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@odys.ma'),
            'name' => env('MAIL_FROM_NAME', 'Odys Rental Management'),
        ],
    ],

    'saas' => [
        'enabled' => env('SAAS_ENABLED', true),
        'default_plan' => env('SAAS_DEFAULT_PLAN', 'starter'),
        'stripe' => [
            'key' => env('SAAS_STRIPE_KEY'),
            'secret' => env('SAAS_STRIPE_SECRET'),
            'webhook_secret' => env('SAAS_STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'tenant' => [
        'domain' => env('TENANT_DOMAIN', 'odys.ma'),
        'database_prefix' => env('TENANT_DATABASE_PREFIX', 'odys_'),
    ],

    'security' => [
        'sanctum' => [
            'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'odys.ma,www.odys.ma')),
        ],
        'cors' => [
            'paths' => ['api/*', 'sanctum/csrf-cookie'],
            'allowed_methods' => ['*'],
            'allowed_origins' => [env('APP_URL', 'https://odys.ma')],
            'allowed_origins_patterns' => [],
            'allowed_headers' => ['*'],
            'exposed_headers' => [],
            'max_age' => 0,
            'supports_credentials' => true,
        ],
    ],

    'performance' => [
        'opcache' => [
            'enabled' => true,
            'memory_consumption' => 128,
            'interned_strings_buffer' => 8,
            'max_accelerated_files' => 4000,
            'revalidate_freq' => 2,
            'validate_timestamps' => false,
        ],
        'cache' => [
            'config' => true,
            'routes' => true,
            'views' => true,
        ],
    ],
];
