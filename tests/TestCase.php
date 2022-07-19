<?php

namespace Artisan\Api\Tests;

use Artisan\Api\ArtisanApiServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    protected $prefix;

    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        $this->apiPrefix = config("artisan.api.prefix");
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
