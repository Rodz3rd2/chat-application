<?php

return [
    'name'        => _env('APP_NAME', "App Name"),
    'namespace'   => _env('APP_NAMESPACE', "App"),
    'environment' => _env('APP_ENV', "development"), // Options: development, production
    'key'         => _env('APP_KEY'),

    # default timezone
    'default_timezone' => "Asia/Manila",

    # Where upload will be stored. The location is `public/uploads`
    'uploads_path' => "uploads",

    # Determine route before application middleware
    'route_on' => true,

    # Application either UP or DOWN(under construction)
    'web_mode' => _env('WEB_MODE'),

    # Commonly use for production
    'use_dist' => filter_var(_env('USE_DIST', false), FILTER_VALIDATE_BOOLEAN),

    # Debug mode
    'debug' => filter_var(_env('DEBUG_ON', false), FILTER_VALIDATE_BOOLEAN),

    # Aliases
    'aliases' => [
        // FrameworkCore Utilities
        'Log'     => FrameworkCore\Utilities\Log::class,
        'Session' => FrameworkCore\Utilities\Session::class,

        // Rapid Authentication library
        'Auth' => AuthSlim\User\Auth\Auth::class,
        'AuthAdmin' => AuthSlim\Admin\Auth\Auth::class
    ]
];