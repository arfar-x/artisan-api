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

use Artisan\Api\Console\UpCommand;
use Artisan\Api\Facades\ArtisanApi;
use Artisan\Api\Middleware\CheckEnvMode;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class ArtisanApiServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/artisan.php', 'artisan');

        $this->publishes([
            __DIR__ . "/../config/artisan.php" => config_path('artisan.php')
        ]);

        /**
         * Prevents application to apply package to container.
         * 
         * This can be modified in config/artisan.php
         */
        if (!config('artisan.auto-run')) return;

        $this->app->bind('artisan.api', function () {
            return new ArtisanApiManager;
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ArtisanApi', ArtisanApi::class);

        /**
         * Publish Events and Listeners
         *
         * Add necessary commands to Artisan:
         *      like:
         *          php artisan api:generate,
         *          php artisan api:list,
         *          php artisan api:up | php artisan api:enable
         *          php artisan api:down | php artisan api:disable
         */
    }

    public function boot()
    {
        //        $configPath = __DIR__ . '/../config/artisan.php';
        //        $this->publishes([$configPath => config_path('artisan.php')], 'config');

        //        $kernel->pushMiddleware(CheckEnvMode::class);
        $this->app->make('router')
            ->aliasMiddleware('artisan.api.env', CheckEnvMode::class)
            ->pushMiddlewareToGroup('api', CheckEnvMode::class);

        $this->app->make('artisan.api')
            ->router()->generate();

        /**
         * Register the commands if the application is running via CLI
         *
         * This is useful while working with Artisan or CLI, otherwise while using
         * HTTP clients (like browsers and APIs), these commands will not be imported.
         * Also can affect on application performance.
         */
        if ($this->app->runningInConsole()) {
            $this->commands($this->getCommands());
        }
    }

    private function getCommands(): array
    {
        return [
            UpCommand::class
        ];
    }
}
