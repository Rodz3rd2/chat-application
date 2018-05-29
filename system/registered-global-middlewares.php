<?php

# register your custom middleware globally.
$app->add(new App\Http\Middlewares\AppMiddleware($container));