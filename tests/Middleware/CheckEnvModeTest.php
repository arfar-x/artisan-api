<?php

namespace Artisan\Api\Tests\Middleware;

use Artisan\Api\Tests\TestCase;

class CheckEnvModeTest extends TestCase
{
    public function testMiddlewareRejectsProductionMode()
    {
        /**
         * TODO Test rejection if application is Production environment
         */
        $this->markTestIncomplete();
    }
}
