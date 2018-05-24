<?php

namespace App\Http\Controllers;

use FrameworkCore\BaseController;

class WelcomeController extends BaseController
{
	public function index($request, $response)
	{
		return $this->view->render($response, "index.twig");
	}
}
