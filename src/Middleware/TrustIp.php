<?php

namespace Artisan\Api\Middleware;

use Artisan\Api\Contracts\MiddlewareInterface;
use Closure;
use Illuminate\Support\Str;

class TrustIp implements MiddlewareInterface
{
    /**
     * Untrusted IPs is aborted
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ips = config('artisan.trust.ip');

        if (! Str::is($ips, $request->ip())) {
            abort(404);
        }

        return $next($request);
    }
}
