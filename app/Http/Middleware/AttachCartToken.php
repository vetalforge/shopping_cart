<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class AttachCartToken
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->cookie('cart_token')) {
            $token = (string) Str::uuid();
            cookie()->queue(cookie('cart_token', $token, 60 * 24 * 7));
        }

        return $next($request);
    }
}
