<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;

class SetCookies {

    public function handle(Request &$request, Closure $next)
    {
        $response = $next($request);
        /**
         * @var Response $response
         */
        if (auth()->user() && session()->has('api-key')) {
            $response->withCookie(cookie('api-key', session()->get('api-key'), 24 * 60));
        }
        return $response;
    }

}
