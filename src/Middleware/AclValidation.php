<?php

namespace Artisan\Api\Middleware;

use Artisan\Api\Contracts\MiddlewareInterface;
use Closure;

/**
 * Access-control List Validation
 */
class AclValidation implements MiddlewareInterface
{
    public function handle($request, Closure $next)
    {
        /**
         * Check and validate requests and packets given by package:
         * 1. ACL Validation ?!
         * 2. Check for hash in URLs
         * 3. Check for trusted IPs and Ports
         * 4. Removing special characters:
         *      & | ; ? () < > / \ !  = - * ^ % $ # @ ' " ` : , . ~ {} [] 
         */

        /**
         * Only trusted roles can access the commands:
         *      E.g. An Admin have an autheticated and valid token;
         *           only roles with authenticated and trusted role name
         *           can access the commands. Tokens for untrusted users'
         *           roles cannot access to the commands
         */

        return $next($request);
    }
}
