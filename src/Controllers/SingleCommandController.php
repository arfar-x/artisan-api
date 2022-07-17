<?php

namespace Artisan\Api\Controllers;

use Artisan\Api\Caller;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SingleCommandController extends BaseController implements CommandControllerInterface
{
    public function run(Request $request)
    {
        $command = $request->command ?? null;
        $subcommand = $request->subcommand ?? null;

        $arguments = $request->query('args');
        $options = $request->query('options');

        $output = Caller::call([
            "command" => $command,
            "subcommand" => $subcommand
        ], $arguments, $options);

        return $output;
    }
}
