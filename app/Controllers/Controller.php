<?php

namespace App\Controllers;

use App\Libs\Authentication;
use App\Libs\Request;
use App\Models\User;

abstract class Controller
{
    private $request;
    private $auth;

    function __construct()
    {
        $this->request = new Request();
        $this->auth = new Authentication($this->request);
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function setRequest(Request $request)
    {
        $this->request = $request;
    }

    protected function getAuth(): ?User
    {
        return $this->auth->getAuth();
    }

    protected function onlyAuthenticated()
    {
        return $this->auth->throwIfUnauthenticated();
    }
}
