<?php

return [
    'driver' => _env('MAIL_DRIVER', "smtp"),
    'host' => _env('MAIL_HOST', "mailtrap.io"),
    'port' => _env('MAIL_PORT', 2525),
    'username' => _env('MAIL_USERNAME', ""),
    'password' => _env('MAIL_PASSWORD', ""),

    'options' => [
        'view_path' => resources_path("views/emails"), // directory of your email template.
        'cache' => is_prod() ? storage_path("cache/mail") : false, // directory of cache folder. If you don't want put false.
        'debug' => true // show error/reason if email not sent.
    ]
];