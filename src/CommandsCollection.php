<?php

namespace Artisan\Api;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan as LaravelArtisan;

class CommandsCollection extends Collection
{

    protected $commands;

    /**
     * TODO there must be way to access Command object via command's name, 
     * like: getObject($commandName) when $commandName = "make:migration"
     * it should return Command{getName() = make:migration}
     */

    public function __construct($items = [], $shouldBeEmpty = false)
    {
        if ($shouldBeEmpty) {
            parent::__construct([]);

            return $this;
        }

        if (! empty($items)) {
            parent::__construct($items);
        } else {
            parent::__construct(LaravelArtisan::all());
        }

        $this->setCommands($this->items);

        return $this;
    }

    protected function setCommands(array $commands = [])
    {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
        $this->setEmpty();

        foreach ($commands as $command => $class) {
            $this->push(new Command($command, $class));
        }

        return $this;
    }

    public function setEmpty()
    {
        $this->items = [];

        return $this;
    }
}
