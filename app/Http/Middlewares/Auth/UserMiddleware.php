<?php

namespace App\Http\Middlewares\Auth;

use App\Models\Auth\User;
use AuthSlim\User\Auth\Auth;
use AuthSlim\User\Middlewares\UserMiddlewareTrait;
use FrameworkCore\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserMiddlewareTrait;
}