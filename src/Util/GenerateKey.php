<?php

namespace Artisan\Api\Util;

use Artisan\Api\Traits\Singleton;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use UnhandledMatchError;

final class GenerateKey
{

    use Singleton;

    /**
     * Generate a unique key for package
     *
     * @param string $algorithm
     * @return string|false
     */
    public function key(string $algorithm)
    {
        return $this->generateRandomKey($algorithm);
    }

    /**
     * Generate a random key.
     *
     * @param [type] $algorithm
     * @return string|false
     * @throws UnhandledMatchError
     */
    protected function generateRandomKey($algorithm)
    {
        // Create the key

        try {
            $appKey = Encrypter::generateKey(app()['config']['app.cipher']);

            $key = match ($algorithm) {
                'base64' => base64_encode($appKey),
                'md5'    => md5($appKey),
                'sha1'   => sha1($appKey),
                'default'=> Crypt::encryptString($appKey)
            };

            if (! $this->writeKeyInConfigFile($key)) {
                return false;
            }

        } catch (UnhandledMatchError) {
            return false;
        }

        return $key;
    }

    /**
     * Write given key into config file array.
     *
     * @param  string  $key
     * @return int|false
     */
    protected function writeKeyInConfigFile($key)
    {
        return file_put_contents($this->getPackageConfigDir(), 
            preg_replace(
                "/'key'\s=>\s(.*)/",
                "'key' => '$key',",
                file_get_contents($this->getPackageConfigDir())
                )
        );
    }

    /**
     * Get package root directory
     *
     * @return string
     */
    protected function getPackageConfigDir()
    {
        return app()["path.artisan-api"] . "/../config/artisan.php";
    }
}
