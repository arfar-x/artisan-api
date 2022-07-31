<?php

namespace Artisan\Api;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * This class is reponsible to call commands.
 */
class Caller
{
    /**
     * Call Artisan command given by `ArtisanApi` controller
     *
     * @param array|string $command
     * @param string $arguments
     * @param string $options
     * @return boolean|string|null
     * 
     * @throws CommandNotFoundException
     * @throws InvalidArgumentException
     * @throws InvalidOptionException
     */
    public static function call(array|string $command, $arguments, $options): bool|string|null
    {
        try {

            if (is_array($command))
                $command = Adapter::getInstance()->toCommand($command["command"], $command["subcommand"]);
            else
                $command = $command["command"];
            
            $arguments = $arguments ? Adapter::getInstance()->toArguments($arguments) : [];
            $options = $options ? Adapter::getInstance()->toOptions($options) : [];

            $parameters = array_merge_recursive($arguments, $options);

            $exitCode = Artisan::call($command, $parameters);

            // exit code `0` means everything works fine
            if ($exitCode != 0)
                throw new \Exception("Something went wrong while runnig command '$command'.");

            Response::getInstance()->setOutput(Artisan::output(), 200);
            
        } catch (CommandNotFoundException) {
            Response::getInstance()->error("Command '$command' called by API not found.", 404);
        } catch (InvalidArgumentException) {

            $argumentsKey = array_keys($arguments)[0];

            $argumentsValue = array_values($arguments)[0];

            Response::getInstance()->error("Argument(s) '$argumentsKey:$argumentsValue' given by an invalid format.", 500);
            
        } catch (InvalidOptionException) {

            $optionsKey = array_keys($options)[0];

            $optionsValue = array_values($options)[0];

            Response::getInstance()->error("Options(s) '$optionsKey:$optionsValue' given by an invalid format.", 500);

        } catch (RuntimeException $e) {
            Response::getInstance()->error($e->getMessage(), 500);
        }

        return Response::getInstance()->getOutput();
    }
}
