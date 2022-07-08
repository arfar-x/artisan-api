<?php

namespace Artisan\Api;

/**
 * This class is responsible to handle Artisan commands,
 * and send $output of each command to the client; in a Json format.
 */
class Response
{
    /**
     * Somthing like:
     *
     * {
     *      "status": "SUCCESS",
     *      "message": "ok",
     *      "generated_file": true|false,
     *      "target_file": "App\Http\Controllers\ArticleController",
     *      "output": "ArticleController controller created successfully",
     * }
     */
}
