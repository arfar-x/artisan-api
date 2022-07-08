<?php

/*
 * This file is part of the Artisan-Http package.
 *
 * (c) Alireza Far <aariow01@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Api;


class ArtisanApiManager
{

    const VERSION = "1.0.0";

    protected CommandsCollection $commands;

    protected Router $router;

    public function __construct($config = null)
    {
        $this->commands = new CommandsCollection;

        $adapter = new RouteAdapter($this->commands);

        $this->router = new Router($adapter);

        return $this;
    }

    public function generateRoutes()
    {
        $this->router->generate();
    }

    /**
     * TODO This is method is only for debugging and should be removed
     *
     * @return Collection
     */
    public function get()
    {
        return $this->commands->all();
    }

    /**
     * TODO useful features:
     *  1. Ability to modify 'api.prefix'
     *  2. Working with hidden commands
     */
}
