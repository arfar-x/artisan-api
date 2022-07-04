<?php

namespace Artisan\Api\Parsers;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

interface ParserInterface
{
    public static function parse(string $command, SymfonyCommand $class = null): array;
}
