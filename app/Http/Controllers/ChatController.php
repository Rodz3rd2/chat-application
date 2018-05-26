<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use FrameworkCore\BaseController;

class ChatController extends BaseController
{
	public function index($request, $response)
	{
        $contacts = User::contacts();
		return $this->view->render($response, "index.twig", compact('contacts'));
	}
}
