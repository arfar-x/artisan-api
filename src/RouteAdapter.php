<?php

namespace Artisan\Api;

/**
 * This class is responsible to extract necessary parameters for dynamic routing; implements Adapter pattern.
 */
class RouteAdapter
{

    /**
     * @var CommandsCollection $commands
     */
    protected CommandsCollection $commands;

    /**
     * Gather all commands to work on them
     *
     * @param CommandsCollection $commands
     */
    public function __construct(CommandsCollection $collectionCommands)
    {
        $this->commands = $collectionCommands;

        return $this;
    }

    /**
     * @return CommandsCollection
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Get command name and return parsed translated URI for API routes
     *
     * @param string $command
     * @return void|string
     */
    public function getUri($command, $withHiddens)
    {
        $command = $this->commands->get($command->getName());

        if ($withHiddens === false && $command->isHidden() === true) return;

        $route = $this->toRoute($command);

        $arguments = $this->toArgumentRoute($command);

        $uri = "/$route/$arguments";

        return $uri;
    }

    /**
     * Replaces `:` with `/` for those commands' name with 'make:model' format
     *
     * @param object $command
     * @return string
     */
    protected function toRoute($command)
    {
        $commandName = str_replace(":", "/", $command->getName());

        return $commandName;
    }

    /**
     * Get command's arguments to route string
     *
     * @param object $command
     * @return string
     */
    protected function toArgumentRoute($command)
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
        if (
            $command->isGenerator()
            || in_array("name", $command->getArguments())
        ) {
            return "{name}";
        }

        return "";
    }
}
