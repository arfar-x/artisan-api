<?php

namespace Artisan\Api\Parsers;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class OptionsParser implements ArrayableParserInterface
{
    public static function parse(string $command, SymfonyCommand $class = null): array
    {
        return static::getOptions($class);
    }

    /**
     * @param SymfonyCommand $class
     * @see SymfonyCommand::getDefinition()::getOptions()
     * @return array
     */
    protected static function getOptions(SymfonyCommand $class): array
    {
        return array_keys($class->getDefinition()->getOptions());
    }
}
