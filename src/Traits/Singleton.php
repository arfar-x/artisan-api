<?php

namespace Artisan\Api\Traits;

/**
 * Implements Singleton design pattern
 */
trait Singleton
{

    /**
     * Singleton instance
     *
     * @var self $_instance
     */
    protected static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            return self::$_instance = new self;
        }

        return self::$_instance;
    }
}