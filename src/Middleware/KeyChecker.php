<?php

namespace Artisan\Api\Middleware;

use Closure;

class KeyChecker
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
