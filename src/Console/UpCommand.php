<?php

namespace Artisan\Api\Console;

use Illuminate\Console\Command;

class UpCommand extends Command
{
    protected $signature = 'api:up';

    protected $description = 'Make Artisan APIs accessible';

    public function handle()
    {
        $this->info('Creating dynamic API endpoints');

        /**
         * Add feature '--force' to run up command forcefully even if in production mode
         */
    }
}
