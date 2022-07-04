<?php

namespace Artisan\Api\Parsers;

use Artisan\Api\Exceptions\CommandParserException;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Console\Parser as LaravelParser;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * This class parses and accesses to necessary information about given command
 */
class CommandParser implements ParserInterface
{
    /**
     * Return formatted command's array
     *
     * @param string $command
     * @param SymfonyCommand $class
     * @return string[]
     */
    public static function parse(string $command, SymfonyCommand $class = null): array
    {
        return [
            $command => $class::class
        ];
    }

    /**
     * @param string $command
     * @param SymfonyCommand $class
     * @return array[]
     * @throws CommandParserException
     *
     * @deprecated
     */
    public static function oldParse(string $command, SymfonyCommand $class): array
    {
        try {

            if (empty($command) || empty($class))
                throw new CommandParserException("Error occured while parsing \"{$command}\" command of " . $class::class . " class: empty parameter given.");

            $properties = (new \ReflectionClass($class))->getDefaultProperties();

            /**
             * If the command has signature, command's detail will be exported from signature,
             * oetherwise, they will be set to null.
             */
            if ($signature = static::getSignature($properties)) {
                [$command, $arguments, $options] = LaravelParser::parse($signature);
            } else {
                [$arguments, $options] = null;
            }

            /**
             * Check for Generator commands like 'make:model' command.
             */
            $generator = (bool)($class instanceof GeneratorCommand);

        } catch (CommandParserException $e) {
            throw new CommandParserException($e->getMessage());
        }

        return [
            $command => [
                "class"     => $class,
                "signature" => $signature,
                "arguments" => $arguments,
                "options"   => $options,
                "generator" => $generator
            ]
        ];
    }

    /**
     * Get command signature from properties returned reflection
     *
     * @param array $properties
     * @return mixed
     *
     * @deprecated
     */
    protected static function getSignature(array $properties)
    {
        return array_key_exists('signature', $properties)
            ? $properties['signature']
            : null;
    }
}
