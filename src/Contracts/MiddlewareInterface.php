<?php

namespace Artisan\Api\Contracts;

use Closure;

interface MiddlewareInterface
{
    /**
     * Run the middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next);
}