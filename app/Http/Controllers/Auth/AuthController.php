<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\User\Controllers\AuthControllerTrait;
use FrameworkCore\BaseController;

class AuthController extends BaseController
{
    use AuthControllerTrait;

    public function successRedirect($response)
    {
        $this->flash->addMessage('success', "Successfully Login.");
        return $response->withRedirect($this->container->router->pathFor('chat-room'));
    }
}