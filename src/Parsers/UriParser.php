<?php

namespace Artisan\Api\Parsers;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class UriParser implements StringableParserInterface
{

    public static function parse(string|array $command)
    {
        $arguments = $command["arguments"];
        $options = $command["options"];



        return $command;
    }
}
