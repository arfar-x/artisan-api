<?php

namespace Artisan\Api;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Command
{
    protected $name;

    protected $class;

    public function __construct($command, SymfonyCommand $class)
    {
        // initialize command into readable format

        $this->name = $command;
        $this->class = $class;

        return $this;
    }

    public function getCommand()
    {
        // Get command's attributes
    }

    public function getName()
    {
        return $this->name;
    }

    public function getClass(bool $toObject = false)
    {
        return $toObject ? $this->class
            : $this->class::class;
    }

    public function getArguments()
    {
        return array_keys($this->class->getDefinition()->getArguments());
    }

    public function getOptions()
    {
        return array_keys($this->class->getDefinition()->getOptions());
    }

    /**
     * Check if command is instance of GeneratorCommand
     *
     * @return bool
     */
    public function isGenerator()
    {
        return (bool)($this->getClass(true) instanceof GeneratorCommand);
    }

    /**
     * Check if command is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return (bool)($this->getClass(true)->isHidden());
    }
}
