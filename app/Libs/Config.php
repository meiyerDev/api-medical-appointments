<?php

namespace App\Libs;

use Dotenv\Dotenv;

class Config
{
    private $dotenv;

    function __construct()
    {
        $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $this->dotenv->load();
    }

    public function getByKey($key, ?string $default = null)
    {
        if ($this->dotenv->ifPresent($key)) return $_ENV[$key];
        return $default;
    }
}
