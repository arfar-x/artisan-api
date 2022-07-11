<?php

namespace Artisan\Api;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan as LaravelArtisan;

/**
 * This class is responsible to contain all commands in a collection.
 * Implements Singleton design pattern.
 */
class CommandsCollection extends Collection
{
    /**
     * Singleton instance
     *
     * @var self $_instance
     */
    protected static $_instance = null;

    public static function getIntance()
    {
        if (is_null(self::$_instance)) {
            return new self;
        }

        return self::$_instance;
    }

    /**
     * Get all Artisan commands, then add them into $items
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $commands = !empty($items) ? $items : LaravelArtisan::all();

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
