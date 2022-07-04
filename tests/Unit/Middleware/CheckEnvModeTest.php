<?php

namespace Artisan\Api\Tests\Middleware;

use Artisan\Api\Middleware\CheckEnvMode;
use Artisan\Api\Tests\TestCase;
use Illuminate\Http\Request;

class CheckEnvModeTest extends TestCase
{
    public function testMiddlewareRejectsProductionMode()
    {
        $env = 'production';
        $debug = false;

        config(['app.env', $env]);
        config(['app.debug', $debug]);

        $request = new Request();

        (new CheckEnvMode())->handle($request, function ($request) {
            $this->assertNotFound();
        });
    }
}
