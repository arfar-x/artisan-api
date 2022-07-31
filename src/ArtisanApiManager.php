<?php

/*
 * This file is part of the Artisan-Api package.
 *
 * (c) Alireza Farhanian <aariow01@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @link https://github/aariow/artisan-api
 */

namespace Artisan\Api;

use Artisan\Api\Contracts\AdapterInterface;
use Artisan\Api\Contracts\RouterInterface;
use IteratorAggregate;

class ArtisanApiManager
{

    protected Router $router;

    public function __construct(AdapterInterface $adapter, IteratorAggregate $commands, RouterInterface $router)
    {
        $adapter->init($commands);
        $router->init($adapter);

        $this->adapter = $adapter;
        $this->router = $router;

        return $this;
    }

    /**
     * Get Router instance
     *
     * @return Router
     */
    public function router(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Get adapter instance
     *
     * @return AdapterInterface
     */
    public function adapter(): AdapterInterface
    {
        return $this->adapter;
    }
}
