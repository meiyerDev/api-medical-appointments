<?php

namespace App\Exceptions;

use Exception;

class Unauthorized extends Exception
{
    function __construct()
    {
        parent::__construct("Action not authorized.", 403);
    }
}
