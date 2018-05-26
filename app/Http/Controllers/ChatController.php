<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use FrameworkCore\BaseController;

class ChatController extends BaseController
{
	public function index($request, $response)
	{
        $contacts = User::contacts();
        $auth = User::find(Auth::user()->id);

		return $this->view->render($response, "index.twig", compact('contacts', 'auth'));
	}
}
