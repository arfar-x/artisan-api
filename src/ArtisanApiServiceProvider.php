<?php

/*
 * This file is part of the Artisan-Http package.
 *
 * (c) Alireza Farhanian <aariow01@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @link https://github/aariow/artisan-http
 */

namespace Artisan\Api;

use Artisan\Api\Facades\ArtisanApi;
use Artisan\Api\Middleware\AbortForbiddenRoute;
use Artisan\Api\Middleware\CheckEnvMode;
use Illuminate\Support\ServiceProvider;

class ArtisanApiServiceProvider extends ServiceProvider
{
    private array $middlewares = [
        'forbidden' => AbortForbiddenRoute::class,
        'env'       => CheckEnvMode::class,
    ];

    private array $commands = [
        Artisan\Api\Console\UpCommand::class,
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/artisan.php', 'artisan');

        $this->publishes([
            __DIR__ . "/../config/artisan.php" => config_path('artisan.php')
        ]);

        /**
         * Prevents application to apply package to container while auto-run is disabled.
         * This can be modified in config/artisan.php
         */
        if (!config('artisan.auto-run')) return;

        $this->app->bind('artisan.api', function () {
            return new ArtisanApiManager;
        });

        // Registers Facade
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ArtisanApi', ArtisanApi::class);
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->app->make('artisan.api')
            ->router()->generate();

        $this->setMiddlewares();

        /**
         * Register the commands if the application is running via CLI
         */
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
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
