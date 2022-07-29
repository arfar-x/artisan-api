<?php 

namespace Artisan\Api\Contracts;

interface RouterInterface
{

    /**
     * Generate routes by dynamic command's attributes; uses RouteAdapter to convert
     * command's attributes into readable string for Laravel routing system.
     *
     * @param boolean $withHiddens
     * @return void
     */
    public function generate(bool $withHiddens = false);
}