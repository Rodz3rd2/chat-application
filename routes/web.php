<?php

$app->get('/', "ChatController:index")
->add(new App\Http\Middlewares\Auth\UserMiddleware($container))
->setName('chat-room');

(new AuthSlim\User\AuthRoute(config('auth')))->routes($app, $container);

$app->get('/authenticated-page', "Auth\AuthController:authenticatedHomePage")
->add(new App\Http\Middlewares\Auth\UserMiddleware($container))
->setName('auth.authenticated-home-page');