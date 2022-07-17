<?php

namespace Artisan\Api;

/**
 * This class is responsible to handle Artisan commands,
 * and send $output of each command to the client; in a Json format.
 */
class Response
{
    private static string $output;

    private static string $status;
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

    public static function setOutput(string $output, $status = null)
    {
        self::$output = $output;
        self::$status = $status ?: $status;
    }

    public static function getOutput()
    {
        return self::$output;
    }

    public static function setStatus(int $status)
    {
        self::$status = $status;
    }

    public static function json()
    {
        return response()->json([
            'output' => self::$output
        ], self::$status);
    }
}
