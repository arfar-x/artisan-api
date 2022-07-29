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

use Artisan\Api\Facades\ArtisanApi;
use Artisan\Api\Middleware\AbortForbiddenRoute;
use Artisan\Api\Middleware\CheckEnvMode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ArtisanApiServiceProvider extends ServiceProvider
{
    private array $middlewares = [
        'forbidden' => AbortForbiddenRoute::class,
        'env'       => CheckEnvMode::class,
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->publish();

        if ($this->shouldBeLoaded()) {
            $this->bind();
        }

        return;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        if ($this->shouldBeLoaded()) {
            
            $this->app->make('artisan.api')
                ->router()->generate();

            $this->setMiddlewares();

        }

        return;
    }

    /**
     * Bind service provider to the application container
     *
     * @return void
     */
    private function bind()
    {
        $this->app->bind('artisan.api', function () {
            return new ArtisanApiManager(CommandsCollection::getInstance(), new Router);
        });

        // Registers Facade
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ArtisanApi', ArtisanApi::class);
    }

    /**
     * Publish all package related files
     *
     * @return void
     */
    private function publish()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/artisan.php', 'artisan');

        $this->publishes([
            __DIR__ . "/../config/artisan.php" => config_path('artisan.php')
        ]);
    }

    /**
     * Evaluate necessary conditions to check whether the package should be loaded or not
     *
     * @return boolean
     */
    private function shouldBeLoaded()
    {
        /**
         * Prevents application to apply package to container while auto-run is disabled.
         * This can be modified in config/artisan.php
         */
        if (!config('artisan.auto-run')) return false;

        if (App::isProduction()) return false;

        return true;
    }

    /**
     * Set route middlewares
     *
     * @return void
     */
    private function setMiddlewares()
    {
        foreach ($this->middlewares as $key => $middleware) {
            $this->app->make('router')
                ->aliasMiddleware($key, $middleware)
                ->pushMiddlewareToGroup('artisan.api', $middleware);
        }
    }
}
