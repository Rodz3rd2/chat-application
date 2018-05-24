<?php

return [
    # cache directory or false to disable
    'cache' => is_prod() ? storage_path("cache/views") : false,

    'views_path' => resources_path(filter_var(_env('USE_DIST', false), FILTER_VALIDATE_BOOLEAN) ? 'dist-views' : "views"),

    # error pages path, relative in views_path
    'error_pages' => [
        "500" => "templates/error-pages/page-500.twig",
        "405" => "templates/error-pages/page-405.twig",
        "404" => "templates/error-pages/page-404.twig",
        "403" => "templates/error-pages/page-403.twig",
        "under-construction" => "templates/error-pages/page-under-construction.twig",
    ]
];