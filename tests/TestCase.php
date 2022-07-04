<?php

namespace Artisan\Api\Tests;

use Artisan\Api\ArtisanApiServiceProvider;
use Orchestra\Testbench\TestCase;

class TestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        return void;
    }

    protected function getPackageProviders($app)
    {
        return [
            ArtisanApiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
