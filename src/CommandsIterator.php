<?php

namespace Artisan\Api;

use Artisan\Api\Traits\Singleton;
use Illuminate\Support\Collection;
use IteratorAggregate;

/**
 * This class is responsible to contain all commands in a collection.
 * Implements Singleton design pattern.
 */
class CommandsIterator extends Collection implements IteratorAggregate
{

    use Singleton;

    /**
     * Get all Artisan commands, then add them into $items
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        parent::__construct($commands);

        $this->setCommands($this->items);

        return $this;
    }

    /**
     * Add given Artisan commands into $items with expected format
     *
     * @param array $commands
     * @return self
     */
    protected function setCommands(array $commands = [])
    {
        /**
         * Here we get all datas in $items with given variable - $commands,
         * we earse $items from Artisan commands within, then we put new
         * expected datas into it.
         */
        $this->setEmpty();

        foreach ($commands as $command => $class) {

            $commandObj = new Command($command, $class);

            $this->put($commandObj->getName(), $commandObj);
        }

        return $this;
    }

    /**
     * Set $items empty
     *
     * @return self
     */
    public function setEmpty()
    {
        $this->items = [];

        return $this;
    }
}
