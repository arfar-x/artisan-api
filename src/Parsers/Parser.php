<?php

namespace Artisan\Api\Parsers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan as LaravelArtisan;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Parser
{
    protected static Collection $commands;

    /**
     * Store all Artisan commands in a Collection then return object
     *
     * @param array $commands
     * @return Collection
     */
    public static function getAllCommands(array $commands): Collection
    {
        return new Collection($commands);
    }

    /**
     * Get all commands formatted by CommandParser class
     *
     * @param array $commands
     * @return Collection
     *
     * @deprecated
     */
    public static function oldGetAllCommands(array $commands): Collection
    {
        static::$commands = new Collection;

        foreach ($commands as $command => $class) {
            static::$commands->push(CommandParser::parse($command, $class));
        }

        return static::$commands->collapse();
    }

    /**
     * Get formatted array for a specific command
     *
     * @param string $command
     * @param $class
     * @return array
     */
    public static function getCommand(string $command, $class): array
    {
        return CommandParser::parse($command, $class);
    }

    /**
     * Get arguments for a specific command
     *
     * @return array
     */
    public static function getArguments(string $command, SymfonyCommand $class): array
    {
        return ArgumentsParser::parse($command, $class);
    }

    /**
     * Get options for a specific command
     *
     * @return array
     */
    public static function getOptions(string $command, SymfonyCommand $class): array
    {
        return OptionsParser::parse($command, $class);
    }
}
