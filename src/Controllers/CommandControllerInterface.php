<?php

namespace Artisan\Api\Controllers;

use Illuminate\Http\Request;

interface CommandControllerInterface
{
    public function run(Request $request);
}