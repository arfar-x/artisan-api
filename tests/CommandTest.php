<?php

namespace Artisan\Api\Tests;

use Artisan\Api\Command;
use Artisan\Api\Tests\TestCase;
use Illuminate\Foundation\Console\ClosureCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{

    private $command;

    public function setUp(): void
    {
        parent::setUp();

        $artisanCommand = Artisan::command('command:test', function () {
            $this->info("command:test run");
        });

        $this->command = new Command("command:test", $artisanCommand);
    }

    public function testGetCurrentCommand()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function testGetClassOfCurrentCommands()
    {
        $this->assertInstanceOf(SymfonyCommand::class, $this->command->getClass(true));
    }

    public function testClosureCommandCanBeTreatedAsClassCommands()
    {
        $this->assertInstanceOf(ClosureCommand::class, $this->command->getClass(true));
    }

    public function testGetAllArguments()
    {
        $this->assertIsArray($this->command->getArguments());
    }

    public function testGetAllOptions()
    {
        $this->assertIsArray($this->command->getOptions());
    }

    public function testCommandIfGenerator()
    {
        $this->assertIsBool($this->command->isGenerator());
    }

    public function testCommandIfHidden()
    {
        $this->assertIsBool($this->command->isHidden());
    }
}
