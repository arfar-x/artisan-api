<?php

namespace Artisan\Api\Contracts;

use Artisan\Api\Command;
use IteratorAggregate;

interface AdapterInterface
{
    
    /**
     * Initialize necessary parameters
     *
     * @param IteratorAggregate $commandsIterator
     * @return self
     */
    public function init(IteratorAggregate $commandsIterator);

    /**
     * Translate command into a string on which router work
     *
     * @param Command $command
     * @param boolean $withHiddens
     * @return string|false
     */
    public function toUri(Command $command, bool $withHiddens): string|false;
    
    /**
     * Convert command into Artisan-like command's name
     *
     * @param string $command
     * @param string $subcommand
     * @return string
     */
    public function toCommand($command, $subcommand = null): string;

    /**
     * Turn argument's string into the following format
     * 
     * ["name" => ArgumentValue]
     * 
     * @param string $arguments
     * @return array
     */
    public function toArguments($arguments): array;

    /**
     * Turn option's string into the following format
     * 
     * ["--model" => OptionValue]
     * 
     * @param string $options
     * @return array
     */
    public function toOptions($options): array;

    /**
     * Check if command is generator
     *
     * @param object $command
     * @return boolean
     */
    public function isGenerator($command): bool;

    /**
     * Get commands iterator instance
     *
     * @return IteratorAggregate
     */
    public function getIterator(): IteratorAggregate;

    /**
     * Get all commands as an iterator array
     * 
     * @return array
     */
    public function getAll(): array;

}