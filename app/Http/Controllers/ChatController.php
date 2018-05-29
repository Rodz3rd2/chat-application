<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Auth;
use FrameworkCore\BaseController;

class ChatController extends BaseController
{
	public function index($request, $response)
	{
        $contacts = User::contacts()->get();
        $initial_conversation = Message::conversation([Auth::user()->id, $contacts->first()->id]);

		return $this->view->render($response, "index.twig", compact('contacts', 'initial_conversation'));
	}
}
