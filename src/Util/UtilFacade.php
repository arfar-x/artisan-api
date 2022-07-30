<?php

namespace Artisan\Api\Util;

use Artisan\Api\Traits\Singleton;

/**
 * TODO Complete it
 * A simple facade interface to deal with Singleton pattern easily
 * while using Util classes. It gives us a handy and lightweight
 * function calling ability.
 */
class UtilFacade
{

    use Singleton;

    /**
     * Do the job
     *
     * @return void
     */
    public static function do($method, $args = [])
    {
        return self::getInstance()->$method(...$args);
    }

    public function __call($method, $args)
    {
        // run called function which is not directly called by object instance
        // mainly call it via an array which holds all methods
    }

    public static function __callStatic($method, $args)
    {
        // run called static function which is not directly called by object instance
        // mainly call it via an array which holds all methods
    }
}
