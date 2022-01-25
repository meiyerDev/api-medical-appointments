<?php

namespace App\Exceptions;

use Exception;

class Unauthenticated extends Exception
{
    function __construct()
    {
        parent::__construct("User not authenticated.", 401);
    }
}
