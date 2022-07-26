<?php

namespace Artisan\Api\Middleware;

use Artisan\Api\Contracts\MiddlewareInterface;
use Closure;

class KeyChecker implements MiddlewareInterface
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
