<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\User\Controllers\AuthControllerTrait;
use FrameworkCore\BaseController;

class AuthController extends BaseController
{
    use AuthControllerTrait;

    public function authenticatedHomePage($request, $response)
    {
        return $this->view->render($response, "index.twig");
    }
}