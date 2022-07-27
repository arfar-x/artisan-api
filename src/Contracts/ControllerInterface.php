<?php

namespace Artisan\Api\Contracts;

use Illuminate\Http\Request;

interface ControllerInterface
{
    public function run(Request $request);
}