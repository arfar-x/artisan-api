<?php

namespace Artisan\Api;

use Artisan\Api\Parsers\Parser;
use Illuminate\Support\Collection;

/**
 * This is responsible to extract necessary parameters for dynamic routing;
 * implements Adapter pattern.
 */
class RouteAdapter
{

    protected Collection $adaptedCommands;

    /**
     * Gather all commands and their arguments and options into a Collection object
     *
     * @param Collection $commands
     * @return Collection
     */
    public function __construct(Collection $commands)
    {
        $adapted = new Collection;

        foreach ($commands->all() as $command => $class) {

            $arguments = Parser::getArguments($command, $class);

            $options = Parser::getOptions($command, $class);

            $adapted->push([
                $command => [
                    "class"     => $class::class,
                    "arguments" => $arguments,
                    "options"   => $options
                ]
            ]);
        }

        $this->adaptedCommands = $adapted->collapse();

        return $this;
    }

    public function getAdaptedCommands()
    {
        return $this->adaptedCommands;
    }
}
