<?php

namespace Artisan\Api;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * This class is responsible to gather information about a command, and provides getter methods
 */
class Command
{
    /**
     * Command name like 'make:mode'
     *
     * @var string
     */
    protected $name;

    /**
     * Command object
     *
     * @var SymfonyCommand
     */
    protected $class;

    /**
     * Arguments geiven from command object
     * 
     * @var array
     */
    protected array $arguments;

    /**
     * Options geiven from command object
     * @var array
     */
    protected array $options;

    /**
     * Check command if generator type
     * 
     * @var bool
     */
    protected bool $generator;

    /**
     * Check command if is hidden and not listed
     * @var array
     */
    protected bool $hidden;

    /**
     * Initializing stage
     *
     * @param string $command
     * @param SymfonyCommand $class
     */
    public function __construct(string $command, SymfonyCommand $class)
    {
        $this->name = $command;
        $this->class = $class;

        $this->setArguments();
        $this->setOptions();
        $this->setIsGenerator();
        $this->setIsHidden();

        return $this;
    }

    /**
     * Get command object as self class
     *
     * @return object
     */
    public function getCommand()
    {
        return $this;
    }

    /**
     * Get command name as string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get class as both object and string
     * Returned object is an instance of Laravel command
     *
     * @param boolean $toObject
     * @return object|string
     */
    public function getClass(bool $toObject = false)
    {
        return $toObject ? $this->class
            : $this->class::class;
    }

    /**
     * Get arguments extracted from command object
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get options extracted from command object
     *
     * @return array
     */
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
