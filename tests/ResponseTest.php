<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Response;
use Artisan\Api\Tests\TestCase;

class ResponseTest extends TestCase
{
    protected $message;

    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->message = "Output is set";

        $this->data = [
            "ok" => true,
            "status" => 200,
            "output" => $this->message
        ];

        Response::getInstance()->setOutput($this->message, 200);
    }

    public function testOutputIsSet()
    {
        $output = Response::getInstance()->getOutput();

        $this->assertIsString($output);
        $this->assertStringContainsString($output, $this->message);
    }

    public function testForResponseFormat()
    {
        $data = Response::getInstance()->json()->getData(assoc: true);

        $this->assertIsArray($data);
        $this->assertEquals($this->data, $data);
    }

    public function testResponseIsJson()
    {
        $data = Response::getInstance()->json()->getContent();

        $this->assertJson($data);
    }

    public function testStatusCodeIsSent()
    {
        $jsonObj = Response::getInstance()->json();

        $data = $jsonObj->getData(assoc: true);

        $status = $jsonObj->getStatusCode();

        $this->assertIsInt($status);
        $this->assertNotNull($status);
        $this->assertEquals($status, $data["status"]);
    }

    public function testResponseIsInstanceOfJsonResponse()
    {
        $data = Response::getInstance()->json();

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $data);
    }
}
