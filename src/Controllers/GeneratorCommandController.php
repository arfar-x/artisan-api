<?php

namespace Artisan\Api\Controllers;

use Artisan\Api\Caller;
use Artisan\Api\Contracts\ControllerInterface;
use Artisan\Api\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class GeneratorCommandController extends BaseController implements ControllerInterface
{
    /**
     * Run commands which are generators
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function run(Request $request)
    {
        $command = $request->command ?? null;
        $subcommand = $request->subcommand ?? null;

        $name = $request->name ? "name:" . $request->name : null;

        if ($arguments = $request->query('args')) {
            $arguments = $name . "," . $arguments;
        } else {
            $arguments = $name;
        }

        $options = $request->query('options');

        Caller::call([
            "command" => $command,
            "subcommand" => $subcommand
        ], $arguments, $options);

        return Response::getInstance()->json();
    }
}
