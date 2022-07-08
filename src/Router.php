<?php

namespace Artisan\Api;

use Artisan\Api\Controllers\RunCommandController;
use Illuminate\Support\Collection;

class Router
{

    protected RouteAdapter $adapter;

    protected Collection $commands;

    protected string|array $method;

    public function __construct(RouteAdapter $adapter)
    {
        $this->adapter = $adapter;

        $this->commands = $this->adapter->getAdaptedCommands();

        $this->method = config('artisan.api.method');
        $this->prefix = config('artisan.api.prefix');

        return $this;
    }

    public function generate(bool $withHiddens = true)
    {

        /**
         * Add static routes like:
         *      /artisan/api/all to get all commands in JSON
         *      /artisan/api/command to send full command in a POST request like:
         *          $command in HTTP-POST : "make:model Article -C --factory"
         */

        $routeConfig = [
            'prefix' => $this->prefix
        ];

        // dd([
        //     "In ". self::class,
        //     $this->getUri("make:migration", $this->commands->all()["make:migration"])
        // ]);

        app('router')->group($routeConfig, function ($router) {

//            $router->addRoute(['GET', 'HEAD'], "/make/migration/{arguments}", function () {
//                dd("Artisan API");
//            });

            foreach ($this->commands->all() as $command => $value) {

                $router->addRoute($this->method, $this->getUri($command, []), $this->getAction($command));

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

    protected function getUri($command, $attributes)
    {
        // return  $this->adapter->getUri($attributes);
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

        /**
         * Algorithm:
         * 1. Check if the command format is like: command:doSomething
         *      then extract it to /command/{subcommand}
         *
         * 2. Check if command has arguments: make:model ModelName
         *      then turn it to /make/model/{arguments=ModelName}
         *
         * 3. Check if command has options: make:model ModelName -C|--controller
         *      then turn it to /make/model/{arguments=ModelName}?options=C|controller
         *      or command vendor:publish --provider="Laravel\Tinker\TinkerServiceProvider"
         *      then turn it to /vendor/publish/?options=provider:Laravel\Tinker\TinkerServiceProvider,force
         *
         * 4. Check if command has nullable arguments: mail:send {userName?}
         *      then generate two APIs:
         *          /mail/send/{arguments=userName}
         *          /mail/send
         *
         * 5. Check if command has multiple input values for arguments: mail:send {user*}
         *      then turn it to: /mail/send/{arg1}/{arg2}/.../{argn}
         *
         * /make/mode/Article?options=controller,factory
         * /make/model?args=Article&options=controller,factory
         * /make/model?input=Article&--queue=default
         *
         * finally this format might a good practice:
         * for Generator commands:
         *      /make/model/{arguments_called_Name}/?args=arg1,arg2&opts=controller,f[acade]
         */

        /**
         * Tips:
         * 1. Generator commands have an arguments called 'name', they may not have signatures
         * 2. Generator commands have a type variable that indicated destination class being created
         * 3. There must be a way to get all options within a Generator command: there is a protected getOptions() method
         */
    }

    /**
     * Get action to be run when route reached
     *
     * @param $command
     * @return string[]
     */
    protected function getAction()
    {
        /**
         * Here we return controller to do actions for cleaner code,
         * we can still use a Closure function to do actions.
         */
        return [RunCommandController::class, 'run'];
    }

    protected function hasAnyArguments(string $command)
    {
        // Check for command if has any arguments, if not generator attribute will be applied.
        // If command has arguments, arguments will be applied to routes.
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
