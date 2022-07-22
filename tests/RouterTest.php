<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Tests\TestCase;

class RouterTest extends TestCase
{
    public function testAllRoutesReachable()
    {
        // route 'prefix/{command}'
        $uri = $this->apiPrefix . '/help';

        $response = $this->post($uri);

        $response->assertOk()
            ->assertJson(["ok" => true])
            ->assertJsonCount(3);

        // route 'prefix/{command}/{subcommand}'
        $uri = $this->apiPrefix . '/optimize/clear';

        $response = $this->post($uri);

        $response->assertOk()
            ->assertJson(["ok" => true])
            ->assertJsonCount(3);

        // route 'prefix/{command}/{subcommand}/{name}'
        $uri = $this->apiPrefix . '/make/model/TEST_MODEL_SHOULD_BE_REMOVED';

        $response = $this->post($uri);

        $response->assertOk()
            ->assertJson(["ok" => true])
            ->assertJsonCount(3);
    }

    public function testIfRouteNotFound()
    {
        $uri = $this->apiPrefix . '/not-existed-command';

        $response = $this->post($uri);

        $response->assertNotFound();
    }

    public function testIfRouteIsWrong()
    {
        $uri = $this->apiPrefix . '/optimizer/clear';

        $response = $this->post($uri);

        $response->assertNotFound();
    }

    public function testThereIsNoRouteForHiddenCommand()
    {
        $uri = $this->apiPrefix . '/down';

        $response = $this->post($uri);

        $response->assertNotFound();
    }

    public function testRouteWithArgument()
    {
        $keyName = "value";
        $uri = $this->apiPrefix . "/cache/forget?args=key:$keyName";

        $response = $this->post($uri);

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson(["ok" => true]);

        $this->assertStringContainsString("The [$keyName] key has been removed from the cache.", $response->getData(true)["output"]);
    }

    public function testRouteWithOption()
    {
        $uri = $this->apiPrefix . "/optimize/clear?options=v";

        $response = $this->post($uri);

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson(["ok" => true]);
    }
}
