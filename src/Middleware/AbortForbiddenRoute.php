<?php

namespace Artisan\Api\Middleware;

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
        $command = $this->getCommand($request);
        
        foreach (config('artisan.forbidden-routes') as $route) {
            if (Str::is($route, $command)) {
                abort(404);
            }
        }

        return $next($request);
    }

    /**
     * Check if the command has subcommand then return it.
     *
     * @param $request
     * @return void
     */
    protected function getCommand($request)
    {
        $command = $request->command ?? null;
        $subcommand = $request->subcommand ?? null;

        if ($subcommand) {
            return "$command:$subcommand";
        }

        return $command;
    }
}
