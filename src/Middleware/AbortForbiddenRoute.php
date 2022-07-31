<?php

namespace Artisan\Api\Middleware;

use Artisan\Api\Adapter;
use Closure;
use Illuminate\Support\Str;

class AbortForbiddenRoute
{

    /**
     * Abort with 404 if called command is forbidden
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $command = Adapter::getInstance()->toCommand($request->command, $request->subcommand);
        
        foreach (config('artisan.forbidden-routes') as $route) {
            if (Str::is($route, $command)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
