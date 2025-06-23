<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class AttachCartToken
{
    public function handle($request, Closure $next)
    {
        if (!$request->cookie('cart_token')) {
            cookie()->queue('cart_token', Str::uuid(), 60*24*30);
        }

        return $next($request);
    }
}
