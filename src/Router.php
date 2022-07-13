<?php

namespace Artisan\Api;

use Artisan\Api\Controllers\RunCommandController;

/**
 * This class is responsible to add routes dynamiccaly and perform related
 * actions on routes.
 */
class Router
{

    /**
     * Default HTTP method; can be set within config/artisan.php
     *
     * @var string|array
     */
    protected string|array $method;

    /**
     * All added routes generated by pacakage
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * Routes that are not commands but should do specific actions
     *
     * @var array
     */
    protected array $staticRoutes = [
        '/all',     // Get all available commands via REST APIs by this package
        '/command', // Provide route to client to add binded command within HTTP request
        '/docs',    // Show documents of available command through APIs
    ];

    /**
     * Initialize necessary parameters
     *
     * @return self
     */
    public function __construct()
    {
        $this->method = config('artisan.api.method');
        $this->prefix = config('artisan.api.prefix');

        $this->forbiddenRoutes = config('artisan.forbidden-routes');

        return $this;
    }

    /**
     * Generate routes by dynamic command's attributes; uses RouteAdapter to convert
     * command's attributes into readable string for Laravel routing system.
     *
     * @param boolean $withHiddens
     * @return void
     */
    public function generate(bool $withHiddens = false)
    {
        $routeConfig = [
            'prefix' => $this->prefix,
            'middleware' => ['api', 'artisan.api']
        ];

        app('router')
            ->group($routeConfig, function ($router) use ($withHiddens) {

            // Add static routes
            foreach ($this->getStaticRoutes() as $route) {
                $router->addRoute($this->method, $route, $this->getAction());
            }

            // Add dynamic routes for each command
            foreach (Adapter::getCommands()->all() as $command) {

                // Prevents empty routes to be added from hidden commands
                if (!$uri = Adapter::toUri($command, $withHiddens))
                    continue;

                $route = $router->addRoute($this->method, $uri, $this->getAction());

                array_push($this->routes, $route->uri);
            }
        });
    }

    /**
     * Get action to be run when route reached
     *
     * @param $command
     * @return array
     */
    protected function getAction()
    {
        /**
         * Here we return controller to do actions for cleaner code,
         * we can still use a Closure function to do actions.
         */
        return [RunCommandController::class, 'run'];
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getStaticRoutes(): array
    {
        return $this->staticRoutes;
    }
}
