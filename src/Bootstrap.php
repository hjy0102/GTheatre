<?php declare(strict_types = 1);

namespace GTheatre;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';

$injector = include('Dependencies.php');
$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');

// var_dump($injector);
// var_dump($request);
// var_dump($response);

/**
* Start session (for global vars)
*/
session_start();

/**
* Register the error handler
*/
$whoops = new \Whoops\Run;
//var_dump($whoops);
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}

$whoops->register();

/**
* Register routes
*/
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        // var_export($className);
        // var_export($method);
        // var_export($vars);

        // var_dump($className);
        // var_dump($method);
        // var_dump($vars);

        $class = $injector->make($className);
        $class->$method($vars);
        break;
}
echo $response->getContent();