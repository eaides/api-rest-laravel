<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *  this is an AfterMiddleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $header
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        /** @var \Illuminate\Http\Request $response */
        $response =  $next($request);

        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
