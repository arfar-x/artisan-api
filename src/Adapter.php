<?php

namespace Artisan\Api;

/**
 * This class is responsible to extract necessary parameters for dynamic routing.
 * Implements Adapter design pattern. Actually, this class is a joint between commands
 * and their routes.
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
     * @param Command $command
     * @param bool $withHiddens
     * @return void|string
     */
    public static function toUri(Command $command, bool $withHiddens)
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
            if (self::isGenerator($command)) {
                return "{name}";
            }

            return "";
        };

        $uri = $route() . "/" . $arguments();

        return $uri;
    }

    /**
     * Check if command is generator
     *
     * @param object $command
     * @return boolean
     */
    public static function isGenerator($command)
    {
        return $command->isGenerator() || in_array("name", $command->getArguments());
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

    /**
     * Turn argument's string into the following format
     * 
     * ["name" => ArgumentValue]
     * 
     * @param string $arguments
     * @return array
     */
    public static function toArguments($arguments)
    {
        $array = self::separator($arguments);

        return $array;
    }

    /**
     * Turn option's string into the following format
     * 
     * ["--model" => OptionValue]
     * 
     * @param string $options
     * @return array
     */
    public static function toOptions($options)
    {
        $array = self::separator($options);

        $keys = array_keys($array);
        $values = array_values($array);

        foreach ($keys as &$key) {
            if (strlen($key) == 1)
                $key = "-$key";
            else
                $key = "--$key";
        }

        return array_combine($keys, $values);
    }

    /**
     * Seprate strings with the format of 'key:value,key2:value2'
     * into an associative array
     *
     * @param string $string
     * @return array
     */
    protected static function separator(string $string): array
    {
        $array = [];

        foreach (explode(",", $string) as $argv) {

            if (strpos($argv, ":")) {
                // If argv has ':', then extract it
                $argsArray = explode(":", $argv);
            } else {
                // If argv does not have ':', then return its value as true
                $argsArray = [$argv, true];
            }

            $array[$argsArray[0]] = $argsArray[1];

        }

        return $array;
    }
}
