<?php

namespace Artisan\Api;

use Artisan\Api\Traits\Singleton;

/**
 * This class is responsible to handle Artisan commands,
 * and send $output of each command to the client; in a Json format.
 */
class Response
{

    use Singleton;

    private string $output = "";

    private int $status = 500;

    /**
     * Set the given output.
     *
     * @param string $output
     * @param int $status
     * @return void
     */
    public function setOutput(string $output, int $status = 200)
    {
        $this->output = $output;
        $this->status = $status;
    }

    /**
     * Return the output that is set before.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set the HTTP status code to be sent.
     *
     * @param integer $status
     * @return void
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Set the given output. This method is implemented for more readability.
     *
     * @param string $error
     * @param int $status
     * @return void
     */
    public function error(string $error, int $status = 500)
    {
        $this->setOutput($error, $status);
    }

    /**
     * Return the output with the type of Json.
     *
     * @param array $data
     * @return Illuminate\Http\JsonResponse
     */
    public function json(array $data = [])
    {
        $ok = ($this->status == 200) ? true : false;

        $data = $data ?: [
            "ok" => $ok,
            "status" => $this->status,
            'output' => $this->output
        ];

        return response()->json($data, $this->status);
    }
}
