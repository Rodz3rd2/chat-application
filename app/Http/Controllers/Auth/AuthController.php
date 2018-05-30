<?php

namespace App\Http\Controllers\Auth;

use App\Models\ChatStatus;
use App\Models\User;
use AuthSlim\User\Auth\Auth;
use AuthSlim\User\Controllers\AuthControllerTrait;
use FrameworkCore\BaseController;

class AuthController extends BaseController
{
    use AuthControllerTrait;

    public function successRedirect($response)
    {
        $auth_id = Auth::user()->id;

        // if user have no chat status
        $chatStatus = ChatStatus::findByUserId($auth_id);
        !is_null($chatStatus) ? $chatStatus->setAsOnline() : ChatStatus::createOnlineUser($auth_id);

        return $response->withRedirect($this->container->router->pathFor('chat-room'));
    }
}