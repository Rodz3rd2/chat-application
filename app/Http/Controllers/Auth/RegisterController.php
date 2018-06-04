<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use AuthSlim\User\Controllers\RegisterControllerTrait;
use FrameworkCore\BaseController;

class RegisterController extends BaseController
{
	use RegisterControllerTrait;

    public function postRegister($request, $response)
    {
        if (!$this->registerRequestIsValid($request))
        {
            return $response->withRedirect($this->container->router->pathFor('auth.register'));
        }

        $input = $request->getParams();
        $pictures = "/img/avatar{n}.png";

        $is_saved = $this->saveInfo([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'picture' => strtr($pictures, ["{n}" => rand(1, 5)])
        ]);

        return $is_saved ? $this->successRedirect($response) : $this->failRedirect($response);
    }

    public function saveInfo($data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'picture' => $data['picture'],
        ]);

        return $user instanceof User;
    }
}