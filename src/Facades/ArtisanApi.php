<?php

namespace Artisan\Api\Facades;

use Illuminate\Support\Facades\Facade;


class ArtisanApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'artisan.api';
    }
}
