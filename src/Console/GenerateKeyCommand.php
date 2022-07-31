<?php

namespace Artisan\Api\Console;

use Artisan\Api\Util\GenerateKey;
use Illuminate\Console\Command;

class GenerateKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisan:key
                            {algo? : Preferred encryption algorithm. Supported: MD5, SHA1, base64 }
                            {--s|show}';

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
        $algo = $this->argument('algo') ?? 'base64';

        $generatedKey = $generator->key($algo);

        if (!$generatedKey) {
            $this->error("Could not generate a key for Artisan-Api.");
            return 1;
        }

        $this->info("Artisan-Api key generated.");


        if ($this->option('show')) {
            $this->newLine();
            $this->info($generatedKey);
        }

        return 0;
    }
}
