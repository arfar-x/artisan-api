<?php

namespace Artisan\Api\Tests\Middleware;

use Artisan\Api\Tests\TestCase;

class CheckEnvModeTest extends TestCase
{
    public function testMiddlewareRejectsProductionMode()
    {
        // Switch APP_ENV to 'production' temporarily, default is 'testing'
        app()['env'] = "production";

        $uri = $this->apiPrefix . "/help";

        $response = $this->post($uri);

        $response->assertNotFound();
    }

    public function testMiddlewareAllowsInDevelopmentMode()
    {
        $uri = $this->apiPrefix . "/help";

        $response = $this->post($uri);

        $response->assertOk();
    }
}
