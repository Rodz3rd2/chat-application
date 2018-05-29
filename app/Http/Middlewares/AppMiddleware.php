<?php

namespace App\Http\Middlewares;

use FrameworkCore\BaseMiddleware;

class AppMiddleware extends BaseMiddleware
{
	public function __invoke($request, $response, $next)
	{
        $this->view->getEnvironment()->addGlobal('namespace', config('app.namespace'));

		return $next($request, $response);
	}
}