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

use Artisan\Api\Console\GenerateKeyCommand;
use Artisan\Api\Facades\ArtisanApi;
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
     * Package commands
     *
     * @var array
     */
    private array $commands = [
        GenerateKeyCommand::class
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

            $this->commands($this->commands);

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

            return new ArtisanApiManager(
                Adapter::getInstance(),
                new CommandsIterator,
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
