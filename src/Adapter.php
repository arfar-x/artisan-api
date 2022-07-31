<?php

namespace Artisan\Api;

use Artisan\Api\Contracts\AdapterInterface;
use Artisan\Api\Traits\Singleton;
use IteratorAggregate;

/**
 * This class is responsible to extract necessary parameters for dynamic routing.
 * Implements Adapter design pattern. Actually, this class is a joint between commands
 * and their routes.
 */
class Adapter implements AdapterInterface
{

    use Singleton;

    /**
     * @var IteratorAggregate $commands
     */
    protected IteratorAggregate $commands;

    /**
     * Gather all commands to work on them
     *
     * @param IteratorAggregate $commands
     */
    public function init(IteratorAggregate $collectionCommands)
    {
        $this->commands = $collectionCommands;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): IteratorAggregate
    {
        return $this->commands;
    }

    /**
     * @inheritDoc
     */
    public function toUri(Command $command, bool $withHiddens): string|false
    {
        $commandName = $command->getName();

        $command = $this->commands->get($commandName);

        if ($withHiddens === false && $command->isHidden() === true) return false;


        // Replaces `:` with `/` for those commands' name with 'make:model' format
        $route = $this->getRoute($commandName);


        // Get command's arguments to route string
        $arguments = $this->getArguments($command);

        $uri = $route . "/" . $arguments;

        return $uri;
    }

    /**
     * Get route of the commands
     *
     * @param string $command
     * @return string
     */
    protected function getRoute(string $command)
    {
        /**
         * If command's name follows 'make:model', then return '{command}/{subcommand}
         * If it follows 'help' or 'list', then return '{command}
         */
        if (preg_match("/(.*):(.*)/", $command)) {
            return "{command}/{subcommand}";
        }

        return "{command}";
    }

    /**
     * Get arguments of the commands
     *
     * @param object $command
     * @return string
     */
    protected function getArguments($command)
    {
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
        if ($this->isGenerator($command)) {
            return "{name}";
        }

        return "";
    }

    /**
     * @inheritDoc
     */
    public function isGenerator($command): bool
    {
        return $command->isGenerator() || in_array("name", $command->getArguments());
    }

    /**
     * @inheritDoc
     */
    public function toCommand($command, $subcommand = null): string
    {
        if ($subcommand) {
            return "$command:$subcommand";
        }

        return $command;
    }

    /**
     * @inheritDoc
     */
    public function toArguments($arguments): array
    {
        $array = $this->separator($arguments);

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function toOptions($options): array
    {
        $array = $this->separator($options);

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
    protected function separator(string $string): array
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
