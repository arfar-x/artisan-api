<?php

namespace Artisan\Api;

use Artisan\Api\Parsers\Parser;
use Illuminate\Support\Collection;

/**
 * This class is responsible to extract necessary parameters for dynamic routing; implements Adapter pattern.
 */
class RouteAdapter
{

    /**
     * @var Collection $adaptedCommands
     */
    protected CommandsCollection $adaptedCommands;

    /**
     * Gather all commands and their arguments and options into a CommandsCollection object
     *
     * @param CommandsCollection $commands
     */
    public function __construct(CommandsCollection $commands)
    {
        // We must initialize $adaptedCommands with an empty instance of CommandsCollection
        $this->adaptedCommands = new CommandsCollection([], shouldBeEmpty: true);

        foreach ($commands->all() as $command) {

            $this->adaptedCommands->put($command->getName(), [
                    "class"     => $command->getClass(),
                    "arguments" => $command->getArguments(),
                    "options"   => $command->getOptions(),
                    "generator" => $command->isGenerator(),
                    "hidden"    => $command->isHidden()
                ]
            );
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAdaptedCommands()
    {
        return $this->adaptedCommands;
    }

    /**
     * Get command name and return parsed translated URI for API routes
     *
     * @param string $command
     * @return mixed
     */
    public function getUri(string|array $command)
    {
        // return $this->getUri($command);
    }
}
