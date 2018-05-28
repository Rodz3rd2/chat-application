<?php

namespace App\Console\Commands;

use App\Chat\Application;
use FrameworkCore\BaseCommand;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatServerCommand extends BaseCommand
{
	/**
	 * Console command signature
	 * @var string
	 */
	private $signature = "serve:chat";

	/**
	 * Console command description
	 * @var string
	 */
	private $description = "";

	/**
	 * Create a new command instance
	 */
	public function __construct()
	{
		parent::__construct($this->signature, $this->description);
	}

	/**
	 * Execute the console command
	 */
	public function handle($input, $output)
	{
		$server = IoServer::factory(
		    new HttpServer(
		    	new WsServer(
		    		new Application
		    	)
		    ),
		    config('chat.port')
		);

		$server->run();
	}
}
