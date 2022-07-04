<?php

use Artisan\Api\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class UpCommandTest extends TestCase
{
    public function testUpCommand()
    {
        $this->markTestIncomplete();

        Artisan::call('api:up');
    }
}
