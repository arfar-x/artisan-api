<?php

namespace Artisan\Api\Parsers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan as LaravelArtisan;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class Parser
{

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
     * Get arguments in array for a specific command
     *
     * @param string $command
     * @param SymfonyCommand $class
     * @return array
     */
    public static function getArguments(string $command, SymfonyCommand $class): array
    {
        return ArgumentsParser::parse($command, $class);
    }

    /**
     * Get options in array for a specific command
     *
     * @param string $command
     * @param SymfonyCommand $class
     * @return array
     */
    public static function getOptions(string $command, SymfonyCommand $class): array
    {
        return OptionsParser::parse($command, $class);
    }

    /**
     * Get string URI for given command, used for routing
     *
     * @param string|array $command
     * @return array|string
     */
    public static function getUri(string|array $command)
    {
        /**
         * We should convert it to string
         */
        return UriParser::parse($command);
    }
}
