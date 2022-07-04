<?php

namespace Artisan\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class CheckEnvMode
{
    public function handle($request, Closure $next)
    {
        abort_if(App::isProduction(), 404);

        return $next($request);
    }
}
