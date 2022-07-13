<?php

namespace Artisan\Api;

/**
 * This class is responsible to extract necessary parameters for dynamic routing.
 * Implements Adapter design pattern.
 */
class Adapter
{

    /**
     * @var CommandsCollection $commands
     */
    protected static CommandsCollection $commands;

    /**
     * Gather all commands to work on them
     *
     * @param CommandsCollection $commands
     */
    public static function init(CommandsCollection $collectionCommands)
    {
        self::$commands = $collectionCommands;
    }

    /**
     * @return CommandsCollection
     */
    public static function getCommands()
    {
        return self::$commands;
    }

    /**
     * Get command name and return parsed translated URI for API routes
     *
     * @param object $command
     * @return void|string
     */
    public static function toUri($command, $withHiddens)
    {
        $commandName = $command->getName();

        $command = self::$commands->get($commandName);

        if ($withHiddens === false && $command->isHidden() === true) return;


        // Replaces `:` with `/` for those commands' name with 'make:model' format
        $route = function () use ($commandName) {
            /**
             * If command's name follows 'make:model', then return '{command}/{subcommand}
             * If it follows 'help' or 'list', then return '{command}
             */
            if (preg_match("/(.*):(.*)/", $commandName)) {
                return "{command}/{subcommand}";
            }

            return "{command}";
        };


        // Get command's arguments to route string
        $arguments = function () use ($command) {
            /**
             * If command is Generator, then an argument called 'name' is mandatory.
             * All Generator commands need 'name' argument.
             * Remained arguments would be gather from URI query. (?args=key:myId)
             * 
             * Some commands like 'make:migration' has an argument called 'name',
             * these kind of commands actually create files, but not classes. So
             * they will NOT be considered as Generator commands. Although, they
             * need arguments to perform on. Consequently we search for 'name' key
             * in command's arguments.
             */
            if (
                $command->isGenerator()
                || in_array("name", $command->getArguments())
            ) {
                return "{name}";
            }

            return "";
        };

        $uri = $route() . "/" . $arguments();

        return $uri;
    }

    /**
     * Convert command into Artisan-like command's name
     *
     * @param string $command
     * @param string $subcommand
     * @return string
     */
    public static function toCommand($command, $subcommand = null)
    {
        if ($subcommand) {
            return "$command:$subcommand";
        }

        return $command;
    }
}
