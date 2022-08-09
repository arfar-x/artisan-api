<?php

return [


    /*
     |--------------------------------------------------------------------------
     | Access key
     |--------------------------------------------------------------------------
     |
     | Just like any other REST APIs you must have a unique key to access it.
     | But there is a difference. In addtion to have the Authentication API keys
     | like 'Bearer', you have to hold a unique key to access Artisan APIs in
     | your HTTP header. It is an actual key to open the door!
     | You can generate a new one by `artisan:key` command.
     |
     */
    'key' => 'your generated key', // not implemented key


    /*
     |--------------------------------------------------------------------------
     | API and endpoints
     |--------------------------------------------------------------------------
     |
     | You can modify the following as you desire.
     |
     */
    'api' => [
        'prefix'    => "/artisan/api",
        'method'    => 'POST', // or ['POST', 'PUT', ...]
        'signature' => '{command}/{subcommand}/{?name}?args={args}' // not implemented yet
    ],


    /*
     |--------------------------------------------------------------------------
     | General running configurations
     |--------------------------------------------------------------------------
     */
    'run' => [
        'only-dev' => false,
        'auto' => true
    ],


    /*
     |--------------------------------------------------------------------------
     | Trust who and what
     |--------------------------------------------------------------------------
     |
     | Here you can allow trusted IPs to go through your commands. You will probably want to access
     | your resources only within local network, so it is a good approach to limit it.
     |
     */
    'trust' => [
        'ip' => [
            '127.0.0.1',

            // To allow anyone connected to your local network
            '192.168.*.*'

            // To allow any IP
            // '*'
        ]
    ],


    /*
     |--------------------------------------------------------------------------
     | Forbidden routes and commands
     |--------------------------------------------------------------------------
     |
     | There are some commands which do sensitive actions. To make them inaccessible,
     | we can put them within the following array. They will not be added to Laravel
     | routing system and will throw '404 NOT_FOUD' HTTP response code.
     | Be aware of some commands like `tinker` and `down` can cause some unexpected behaviors
     | while accessing via APIs, to prevent your application crash, we recognize them as
     | forbidden commands by default. If you call command `down`, your application will
     | suspend to maintenance mode. Then you have to access SSH to run `up` command or consider
     | other tricks to put your application into production mode.
     |
     */
    'forbidden-routes' => [
        'clear-compiled',
        'tinker',
        'up',
        'down',
        'serve',
        'completion',
        '_complete',
        'migrate*',
        'db*',
        '*publish',
        'key:generate',
        'artisan:key'
    ],



    /*
     |--------------------------------------------------------------------------
     | Middlwares
     |--------------------------------------------------------------------------
     |
     | You are extremely encouraged to implement your policy and access-control middleware
     | and inject it to Artisan-Api. As all projects have role-based limitation, it is possible
     | to add your own.
     |
     */
    'middlewares' => [
        'ip'        => Artisan\Api\Middleware\TrustIp::class,
        'key'       => Artisan\Api\Middleware\KeyChecker::class,
        'forbidden' => Artisan\Api\Middleware\AbortForbiddenRoute::class,
        'env'       => Artisan\Api\Middleware\CheckEnvMode::class,
    ]
];
