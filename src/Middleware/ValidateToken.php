<?php

namespace Artisan\Api\Middleware;

use Closure;

class ValidateToken
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
