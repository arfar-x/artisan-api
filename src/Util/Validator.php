<?php

namespace Artisan\Api\Util;

use Artisan\Api\Contracts\ValidatorInterface;

class Validator extends UtilFacade implements ValidatorInterface
{
    /**
     * Works with rules to validate data; can be used by AclValidation
     */
}
