<?php

namespace App\Http\Middlewares\Auth;

use AuthSlim\User\Middlewares\GuestMiddlewareTrait;
use FrameworkCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}