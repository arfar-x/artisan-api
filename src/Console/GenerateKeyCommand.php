<?php

namespace Artisan\Api\Console;

use Artisan\Api\Util\GenerateKey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisan:key
                            {algo? : Preferred encryption algorithm}
                            {--c|count=32 : Key character size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a public key for your Artisan APIs to access with.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(GenerateKey $generator)
    {
        // TODO Implement it
    }
}
