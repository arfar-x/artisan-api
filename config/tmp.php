<?php

return [

    // 'kkeys' => config('artisan.key', 'your_key'),

    'key' => 'your_key',

    'api' => [
        'prefix'    => "/artisan/api",
        'method'    => ['POST', 'GET', 'HEAD'],
        'signature' => '{command}/{subcommand}/{?name}'
    ],

    'auto-run' => true,


    /*
     |--------------------------------------------------------------------------
     | Trust who or what
     |--------------------------------------------------------------------------
     |
     | Here you can allow users with specific roles and IPs to go through your commands.
     | It can be really useful while having some users which can gain API tokens to play with
     | other resources, so they are limited to call commands.
     | On the other hand, you might want to access endpoints with a particular and private IP
     | and Port.
     |
     */
    'trust' => [

        'roles or users' => [
            'admin', 'manager'
        ],

        'ip' => [
            '127.0.0.1',
            'localhost',
            '0.0.0.0',
        ],

        'port' => [
            80,
            443,
            8000,
            8080
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
        'db*',
        '*publish',
        'key:generate',
        'artisan:key'
    ]
];
