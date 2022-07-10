<?php

return [

    'api' => [
        'prefix' => "/artisan/api",
        'method'    => ['GET', 'HEAD'],
    ],

    'auto-run' => true,

    /**
     * These commands will NOT be added to Laravel routes, and CANNOT be access
     * via APIs
     */
    'forbidden-routes' => [
        'tinker',
        'down',
        'serve',
        'completion',
        'up',
        'db:seed',
    ]
];
