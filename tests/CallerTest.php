<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Caller;
use Artisan\Api\Tests\TestCase;

class CallerTest extends TestCase
{

    /**
     * Default command
     *
     * @var $command
     */
    protected $command;

    /**
     * Default subcommand
     *
     * @var $subcommand
     */
    protected $subcommand;

    /**
     * Default arguments
     *
     * @var $arguments
     */
    protected $arguments;

    /**
     * Default options
     *
     * @var $options
     */
    protected $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->command = "make";
        $this->subcommand = "model";
        $this->arguments = "name:TESTMODEL_" . uniqid();
        $this->options = "f,controller:true"; // Create a factory and controller with model
    }

    public function testCommandWorksPerfectly()
    {
        $output = $this->callCommand();

        $this->assertIsString($output);
        $this->assertNotNull($output);
        $this->assertStringContainsString("Model created successfully.", $output);
        $this->assertStringContainsString("Factory created successfully.", $output);
        $this->assertStringContainsString("Controller created successfully.", $output);
    }

    public function testForUnreadableArguments()
    {
        $this->arguments = "wrong_argument_name:value";

        $output = $this->callCommand();

        $this->assertIsString($output);
        $this->assertStringContainsString("Argument(s) '$this->arguments' given by an invalid format.", $output);
    }

    public function testForUnreadableOptions()
    {
        $this->options = "wrong_option_name:value";

        $output = $this->callCommand();

        $this->assertIsString($output);
        $this->assertStringContainsString("Options(s) '--$this->options' given by an invalid format.", $output);
    }

    public function testForMissingArguments()
    {
        $this->arguments = $this->options = "";

        $output = $this->callCommand();

        $this->assertIsString($output);
        $this->assertStringContainsString("Not enough arguments (missing: \"name\").", $output);
    }

    public function testCommandNotFound()
    {
        $this->command = "not_existed_command";
        $this->subcommand = "something";

        $output = $this->callCommand();

        $this->assertIsString($output);
        $this->assertStringContainsString("Command '$this->command:$this->subcommand' called by API not found.", $output);
    }

    /**
     * Call inputed command by Caller class
     *
     * @return string
     */
    protected function callCommand()
    {
        return Caller::call([
            "command" => $this->command,
            "subcommand" => $this->subcommand
        ], $this->arguments, $this->options);
    }
}
