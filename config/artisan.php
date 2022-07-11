<?php

return [

    'api' => [
        'prefix' => "/artisan/api",
        'method'    => ['GET', 'HEAD'],
    ],

    'auto-run' => true,

    /*
     |--------------------------------------------------------------------------
     | Forbidden routes and commands
     |--------------------------------------------------------------------------
     |
     | There are some commands which do sensitive actions .To make them inaccessible,
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
        'tinker',
        'up',
        'down',
        'serve',
        'completion',
        '_complete',
        'db*',
        '*publish'
    ]
];
