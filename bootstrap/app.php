<?php

$system = new FrameworkCore\System;

# Framework Application
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => config('app.debug'),

        'addContentLengthHeader' => false, // if true = Unexpected data in output buffer

        'determineRouteBeforeAppMiddleware' => config('app.route_on'),

        'db' => config('database.database_connection.mysql'),
        'tracy' => config('debug-bar.tracy.settings'),

        'mail' => config('mail')
    ]
]);

# Application Container
$container = $app->getContainer();

$system->boot($app, $container);

return $app;