<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Tests\TestCase;

class AbortForbiddenRouteTest extends TestCase
{
    public function testIfForbiddenRouteIsCalled()
    {
        $forbiddenRoute = $this->apiPrefix . "/down";

        $response = $this->post($forbiddenRoute);

        $response->assertNotFound();
    }

    public function testValidCommandIsCalled()
    {
        $uri = $this->apiPrefix . "/help";

        $response = $this->post($uri);

        $response->assertOk();
        $this->assertNotEmpty($response->getContent());
        $this->assertJson($response->getContent());
    }
}
