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
use Illuminate\Support\Facades\Artisan as LaravelArtisan;
use Illuminate\Support\ServiceProvider;

class ArtisanApiServiceProvider extends ServiceProvider
{

    /**
     * Package middlewares to be pushed
     *
     * @var array
     */
    private array $middlewares = [];

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

            $this->setMiddlewares();
        }
    }

    /**
     * @inheritDoc
     * 
     * We have to initialize the package a feed the commands after
     * loading all providers by Laravel
     */
    public function callBootedCallbacks()
    {
        $this->app->call(
            function () {
                $this->app->make('artisan.api')
                    ->init(new CommandsIterator( LaravelArtisan::all() ))
                    ->router()->generate();
            }
        );
    }

    /**
     * Bind service provider to the application container
     *
     * @return void
     */
    private function bind()
    {
        $this->app->singleton('artisan.api', function () {

            return new ArtisanApiManager(
                Adapter::getInstance(),
                new Router
            );
        });

        $this->app->instance('path.artisan-api', __DIR__);

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

        $this->middlewares = config('artisan.middlewares');
    }

    /**
     * Evaluate necessary conditions to check whether the package should be loaded or not
     *
     * @return boolean
     */
    private function shouldBeLoaded()
    {
        if (!config('artisan.run.auto')) return false;

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
