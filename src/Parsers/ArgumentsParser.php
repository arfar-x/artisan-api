<?php

namespace Artisan\Api\Parsers;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class ArgumentsParser implements ArrayableParserInterface
{
    /**
     * Return all arguments of the given command
     *
     * @param string $command
     * @param SymfonyCommand|null $class
     * @return array
     */
    public static function parse(string $command, SymfonyCommand $class = null): array
    {
        return static::getArguments($class);
    }

    /**
     * @param SymfonyCommand $class
     * @see SymfonyCommand::getDefinition()::getArguments()
     * @return array
     */
    protected static function getArguments(SymfonyCommand $class): array
    {
        return array_keys($class->getDefinition()->getArguments());
    }
}
