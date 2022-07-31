<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Tests\TestCase;

class TrustIpTest extends TestCase
{

    protected string $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->route = $this->apiPrefix . "/list";
    }

    public function testAnUntrustedIpIsAborted()
    {
        // Impossible !
        $untrustedIp = '256.0.0.0';

        config(['artisan.trust.ip' => [$untrustedIp]]);

        $response = $this->post($this->route);

        $response->assertNotFound();
    }

    public function testATrustedIpIsAllowed()
    {
        config(['artisan.trust.ip' => ['127.0.0.1']]);

        $response = $this->post($this->route);

        $response->assertOk();
    }

    public function testAnyIpIsAllowed()
    {
        config(['artisan.trust.ip' => ['*']]);

        $response = $this->post($this->route);

        $response->assertOk();
    }

    public function testNoIpIsAllowed()
    {
        config(['artisan.trust.ip' => []]);

        $response = $this->post($this->route);

        $response->assertNotFound();
    }
}
