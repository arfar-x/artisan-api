<?php

return [

    'api' => [
        'prefix' => "artisan/api",
        'signature' => "{command}/{subcommand}/{arguments}?options={options}",
        'method'    => 'POST'
        'separator' => ",",
    ],

    'auto_run' => true
];
