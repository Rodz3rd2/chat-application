<?php

/**
 * This file is auto updating the content. Do not modify it unless you know what you are doing.
 */

# ChatController
$container['ChatController'] = function ($c)
{
    return new App\Http\Controllers\ChatController($c);
};

# Auth\AccountDetailController
$container['Auth\AccountDetailController'] = function ($c)
{
    return new App\Http\Controllers\Auth\AccountDetailController($c);
};

# Auth\AuthController
$container['Auth\AuthController'] = function ($c)
{
    return new App\Http\Controllers\Auth\AuthController($c);
};

# Auth\ForgotPasswordController
$container['Auth\ForgotPasswordController'] = function ($c)
{
    return new App\Http\Controllers\Auth\ForgotPasswordController($c);
};

# Auth\RegisterController
$container['Auth\RegisterController'] = function ($c)
{
    return new App\Http\Controllers\Auth\RegisterController($c);
};

# Auth\ResetPasswordController
$container['Auth\ResetPasswordController'] = function ($c)
{
    return new App\Http\Controllers\Auth\ResetPasswordController($c);
};

