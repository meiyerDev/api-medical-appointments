<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    function __construct()
    {
        parent::__construct("Model not found.", 404);
    }
}
