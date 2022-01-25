<?php

namespace App\Exceptions;

use Exception;

class MethodNotAllowed extends Exception
{
    function __construct(string $method)
    {
        $method = ucwords($method);
        parent::__construct("Method {$method} not allowed.", 405);
    }
}
