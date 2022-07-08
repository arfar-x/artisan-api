<?php

namespace Artisan\Api\Parsers;

interface StringableParserInterface
{
    public static function parse(string|array $command);
}
