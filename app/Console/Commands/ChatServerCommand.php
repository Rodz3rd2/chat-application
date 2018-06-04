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
	private $signature = "serve:chat {--host= : Chat host name} {--port= : Chat port}";

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
		$host = $input->getOption('host');
		$port = $input->getOption('port');

		$server = IoServer::factory(
		    new HttpServer(
		    	new WsServer(
		    		new Application
		    	)
		    ),
		    !is_null($port) ? $port : config('chat.port'),
		    !is_null($host) ? $host : config('chat.hostname')
		);

		$server->run();
	}
}
