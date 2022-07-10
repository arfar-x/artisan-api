<?php

namespace Artisan\Api\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

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

        dd([
            "path" => $request->path(),
            "calledIn" => class_basename($this) . "@" . __FUNCTION__,
            "Method" => $request->method()
        ]);
    }
}
