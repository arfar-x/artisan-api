<?php

namespace Artisan\Api\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RunCommandController extends BaseController
{
    public function run(Request $request)
    {
        $command = $request->command ?? null;
        $subcommand = $request->subcommand ?? null;
        $name = $request->name ?? null;

        if ($subcommand) {
            $command = $request->command . ":" . $request->subcommand;
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
