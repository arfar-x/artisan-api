<?php

namespace Artisan\Api;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * This class is responsible to gather information about a command, and provides getter methods
 */
class Command
{
    protected $name;

    protected $class;

    protected array $arguments;

    protected array $options;

    protected bool $generator;

    protected bool $hidden;

    public function __construct($command, SymfonyCommand $class)
    {
        $this->name = $command;
        $this->class = $class;

        $this->setArguments();
        $this->setOptions();
        $this->setIsGenerator();
        $this->setIsHidden();

        return $this;
    }

    public function getCommand()
    {
        return $this;
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
        return $this->arguments;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Check if command is instance of GeneratorCommand
     *
     * @return bool
     */
    public function isGenerator()
    {
        return $this->generator;
    }

    /**
     * Check if command is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Set arguments if current command
     *
     * @return self
     */
    protected function setArguments()
    {
        $this->arguments = array_keys($this->class->getDefinition()->getArguments());

        return $this;
    }

    /**
     * Set options of current command
     *
     * @return self
     */
    protected function setOptions()
    {
        $this->options = array_keys($this->class->getDefinition()->getOptions());

        return $this;
    }

    /**
     * Set generator as whether command is instance of GeneratorCommand or not.
     *
     * @return self
     */
    protected function setIsGenerator()
    {
        $this->generator = (bool)($this->getClass(true) instanceof GeneratorCommand);

        return $this;
    }

    /**
     * Set hidden whether command is hidden or not.
     *
     * @return self
     */
    protected function setIsHidden()
    {
        $this->hidden = (bool)($this->getClass(true)->isHidden());

        return $this;
    }
}
