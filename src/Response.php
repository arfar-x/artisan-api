<?php

namespace Artisan\Api;

/**
 * This class is responsible to handle Artisan commands,
 * and send $output of each command to the client; in a Json format.
 */
class Response
{
    private static string $output;

    private static int $status;

    /**
     * Set the given output.
     *
     * @param string $output
     * @param int $status
     * @return void
     */
    public static function setOutput(string $output, int $status = null)
    {
        self::$output = $output;
        self::$status = $status ?: $status;
    }

    /**
     * Return the output that is set before.
     *
     * @return string
     */
    public static function getOutput()
    {
        return self::$output;
    }

    /**
     * Set the HTTP status code to be sent.
     *
     * @param integer $status
     * @return void
     */
    public static function setStatus(int $status)
    {
        self::$status = $status;
    }

    /**
     * Set the given output. This method is implemented for more readability.
     *
     * @param string $error
     * @param int $status
     * @return void
     */
    public static function error(string $error, int $status = null)
    {
        self::setOutput($error, $status);
    }

    /**
     * Return the output with the type of Json.
     *
     * @param array $data
     * @return Illuminate\Http\JsonResponse
     */
    public static function json(array $data = [])
    {
        $ok = (self::$status == 200) ? true : false;

        $data = $data ?: [
            "ok" => $ok,
            "status" => self::$status,
            'output' => self::$output
        ];

        return response()->json($data, self::$status);
    }
}
