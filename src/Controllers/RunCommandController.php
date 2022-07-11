<?php

namespace Artisan\Api\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class RunCommandController extends BaseController
{
    public function run(Request $request)
    {
        /**
         * Here we should cal the Artisan command like this:
         *      Artisan::call($destinationClass, $parsed_Arguments_and_Options_From_Api_Call, ...);
         *
         * Or maybe we should create an in-memory controller to controller the router action.
         *      This idea has a little posibbility to be needed
         */

        // dd(app('router')->getRoutes());

        $command = $request->command ?? null;
        $subcommand = $request->subcommand ?? null;
        $name = $request->name ?? null;

        if ($subcommand) {
            $command = $request->command . ":" . $request->subcommand;
        }

        foreach (config('artisan.forbidden-routes') as $route) {
            if (Str::is($route, $command)) {
                abort(404);
            }
        }

        if (is_null($name))
        {
            $parameters = [];
        } else {
            $parameters = ["name" => $name];
        }

        Artisan::call($command, $parameters);

        $output = Artisan::output();

        dd([
            "command" => $command,
            "subcommand" => $subcommand,
            "argument_name" => $name,
            "output" => $output,
            "request" => ["path" => $request->path(),
            "calledIn" => class_basename($this) . "@" . __FUNCTION__,
            "Method" => $request->method()]
        ]);
    }
}
