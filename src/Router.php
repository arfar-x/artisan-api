<?php

namespace Artisan\Api;

use Illuminate\Support\Collection;

class Router
{
    protected string $method;

    protected array $routes = [];

    protected Collection $commands = [];

    public function __construct(Collection $commands)
    {
        $this->commands = $commands;

        $this->method = config('artisan.api.method');

        return $this;
    }

    protected function generate(bool $withHiddens = true)
    {
        app('router')->group($routeConfig, function ($router) {

            foreach ($this->commands->all() as $command => $class) {

                $router->addRoute($this->method, $this->getUri($command), $this->getAction($command));

            }

        });

        // perform dynamic routes based on Artisan commands, via CommandParser class

        // Maybe useful for dynamic route generation
//        app('router')->group($routeConfig, function ($router) {
//            $router->get('open', [
//                'uses' => 'OpenHandlerController@handle',
//                'as' => 'debugbar.openhandler',
//            ]);
//        });
    }

    protected function getUri($command)
    {
        /**
         * E.g. command = "make:migration"
         *      uri return: artisan/api/make/migration/{arguments}?options={options}
         *
         * E.g. command = "make:model"
         *      uri return: artisan/api/make/model/{modelName}?options={options}
         *      there are no arguments for most of make:* commands
         *
         * E.g. command = "help"
         *      uri return: artisan/api/help/{arguments = command_name}?options={options}
         *      there should be a validation for commands with no arguments or options
         */

        return "";
    }

    protected function getAction($command)
    {
        /**
         * Here we should cal the Artisan command like this:
         *      Artisan::call($destinationClass, $parsed_Arguments_and_Options_From_Api_Call, ...);
         *
         * Or maybe we should create an in-memory controller to controller the router action.
         *      This idea has a little posibbility to be needed
         */
    }

    public function setRoutes(string $route)
    {
        return $this->routes = array_push($this->routes, $route);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
