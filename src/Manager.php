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

use Artisan\Api\Parsers\CommandParser;
use Artisan\Api\Parsers\Parser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan as LaravelArtisan;

class Manager
{
    const VERSION = "1.0.0";

    protected $config;

    protected $routes = [];

    protected Collection $commands;

    public function __construct($config = null)
    {
        $this->config = config("artisan");

        $this->commands = $this->parseCommands();

        $adapter = new RouteAdapter($this->commands);

        $this->router = new Router($adapter->getAdaptedCommands());

        return $this;
    }

    /**
     * Parse each Artisan command to desired format
     *
     * @return Collection
     */
    protected function parseCommands()
    {
        return Parser::getAllCommands(LaravelArtisan::all());
    }

    protected function generateRoutes()
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
     *  1. Ability to enter customized 'api_signature'
     *  2. Ability to modify 'api_prefix'
     *  3. Working with hidden commands
     */
}
