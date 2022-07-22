<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Adapter;
use Artisan\Api\CommandsCollection;
use Artisan\Api\Tests\TestCase;

class AdapterTest extends TestCase
{

    protected CommandsCollection $collection;

    public function setUp(): void
    {
        parent::setUp();

        $this->collection = CommandsCollection::getIntance();

        Adapter::init($this->collection);
    }

    public function testGetCommandsUri()
    {
        $command = $this->command("cache:clear");
        $routeSignature = "{command}/{subcommand}/";

        $uri = Adapter::toUri($command, true);

        $this->assertIsString($uri);
        $this->assertEquals($routeSignature, $uri);
    }

    public function testGetCommandAsCommandName()
    {
        $name = Adapter::toCommand("make", "model");

        $this->assertMatchesRegularExpression("/(.*):(.*)/", $name);
    }

    public function testStringArgumentsIntoArray()
    {
        $stringArgs = "arg1:something,key:value,key2:value2,nullValue,assoc:something";

        $arrayArgs = Adapter::toArguments($stringArgs);

        $this->assertIsArray($arrayArgs);

        $this->assertArrayHasKey("arg1", $arrayArgs);
        $this->assertArrayHasKey("key", $arrayArgs);
        $this->assertArrayHasKey("nullValue", $arrayArgs);
        $this->assertArrayHasKey("assoc", $arrayArgs);

        $this->assertEquals("something", $arrayArgs["arg1"]);
        $this->assertEquals("value", $arrayArgs["key"]);
        $this->assertEquals("something", $arrayArgs["assoc"]);

        $this->assertTrue($arrayArgs["nullValue"]);

        $this->assertCount(5, $arrayArgs);
    }

    public function testStringOptionsIntoArray()
    {
        $stringArgs = "opt1:something,key:value,key2:value2,nullValue,assoc:something,v,c";

        $arrayArgs = Adapter::toOptions($stringArgs);

        $this->assertIsArray($arrayArgs);

        $this->assertArrayHasKey("--opt1", $arrayArgs);
        $this->assertArrayHasKey("--key", $arrayArgs);
        $this->assertArrayHasKey("--nullValue", $arrayArgs);
        $this->assertArrayHasKey("--assoc", $arrayArgs);
        $this->assertArrayHasKey("-v", $arrayArgs);
        $this->assertArrayHasKey("-c", $arrayArgs);

        $this->assertEquals("something", $arrayArgs["--opt1"]);
        $this->assertEquals("value", $arrayArgs["--key"]);
        $this->assertEquals("something", $arrayArgs["--assoc"]);

        $this->assertTrue($arrayArgs["--nullValue"]);
        $this->assertTrue($arrayArgs["-v"]);
        $this->assertTrue($arrayArgs["-c"]);

        $this->assertCount(7, $arrayArgs);
    }

    public function testIfCommandIsInstanceOfGenerator()
    {
        $command = $this->command("make:model");

        $isGenerator = Adapter::isGenerator($command);

        $this->assertTrue($isGenerator);
    }

    public function testIfCommandIsGeneratorEvenIfNotInstanceOfGeneratorCommand()
    {
        $command = $this->command("make:migration");

        $isGenerator = Adapter::isGenerator($command);

        $this->assertTrue($isGenerator);
    }

    /**
     * Get inputed command object from CommandsCollection
     *
     * @param string $name
     * @return object
     */
    protected function command($name)
    {
        return $this->collection->get($name);
    }
}
