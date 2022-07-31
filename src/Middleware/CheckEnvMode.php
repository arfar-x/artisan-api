<?php

namespace Artisan\Api\Middleware;

use Artisan\Api\Contracts\MiddlewareInterface;
use Closure;
use Illuminate\Support\Facades\App;

class CheckEnvMode implements MiddlewareInterface
{
    /**
     * Abort with 404 when application is in production mode and run.only-dev is true config.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('artisan.run.only-dev')) {
            abort_if(App::isProduction(), 404);
        }

        return $next($request);
    }
}
