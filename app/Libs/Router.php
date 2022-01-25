<?php

namespace App\Libs;

use App\Controllers\BranchController;
use App\Controllers\DateController;
use App\Controllers\LoginController;
use App\Exceptions\MethodNotAllowed;

class Router
{
    /**
     * Array de rutas disponibles
     */
    private $routes = [
        'POST|register/patient' => [LoginController::class, 'registerPatient'],
        'POST|register/doctor' => [LoginController::class, 'registerDoctor'],
        'POST|login' => [LoginController::class, 'login'],
        'POST|logout' => [LoginController::class, 'logout'],
        'GET|branches' => [BranchController::class, 'index'],
        'POST|dates' => [DateController::class, 'store'],
        'GET|dates/not-confirmed' => [DateController::class, 'getDatesNotConfirmed'],
        'POST|dates/confirm' => [DateController::class, 'confirmDate'],
        'GET|auth/dates' => [DateController::class, 'getByAuth'],
        'GET|auth/dates/today' => [DateController::class, 'getDatesOfDayByAuth'],
    ];

    private $controller;
    private $method;

    function __construct($route)
    {
        if (!isset($_SESSION)) session_start();

        $method = $_SERVER['REQUEST_METHOD'];
        $route = $method . '|' . $route;

        if (!isset($this->routes[$route])) throw new MethodNotAllowed($method);

        $routeController = $this->routes[$route];
        // ['Controller', 'method']
        //       0            1
        $this->controller = new $routeController[0]($method);
        $this->method = $routeController[1];
    }

    public function loadResponse()
    {
        return $this->controller->{$this->method}();
    }
}
