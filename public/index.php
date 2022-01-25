<?php

use App\Libs\Response;
use App\Libs\Router;

require __DIR__ . '/../vendor/autoload.php';

try {
    $route = new Router($_GET['url']);
    echo $route->loadResponse();
} catch (\Exception $th) {
    $response = new Response([
        'error' => $th->getMessage(),
    ], $th->getCode());

    echo $response->toJson();
}
