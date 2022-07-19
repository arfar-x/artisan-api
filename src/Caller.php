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
                $command = Adapter::toCommand($command["command"], $command["subcommand"]);
            else
                $command = $command["command"];
            
            $arguments = $arguments ? Adapter::toArguments($arguments) : [];
            $options = $options ? Adapter::toOptions($options) : [];

            $parameters = array_merge_recursive($arguments, $options);

            $exitCode = Artisan::call($command, $parameters);

            // exit code `0` means everything works fine
            if ($exitCode != 0)
                throw new \Exception("Something went wrong while runnig command '$command'.");

            Response::setOutput(Artisan::output(), 200);
            
        } catch (CommandNotFoundException) {
            Response::error("Command '$command' called by API not found.", 404);
        } catch (InvalidArgumentException) {
            Response::error("Argument(s) '". implode(', ', $arguments) ."' given by an invalid format.", 500);
        } catch (InvalidOptionException) {
            Response::error("Options(s) '". implode(', ', $options) ."' given by an invalid format.", 500);
        } catch (RuntimeException $e) {
            Response::error($e->getMessage(), 500);
        }

        return Response::getOutput();
    }
}
