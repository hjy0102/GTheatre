<?php declare(strict_types = 1);

/**
* auryn is a recursive dependency injector. Use auryn to bootstrap and wire together S.O.L.I.D., 
* object-oriented PHP applications. REFER TO:
* https://github.com/rdlowrey/auryn
**/ 

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');

$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER
]);

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');

$injector->alias('GTheatre\Template\Renderer', 'GTheatre\Template\TwigRenderer');

$injector->delegate('Twig_Environment', function() use ($injector) {
   $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
   $twig = new Twig_Environment($loader);
   return $twig;
});

$injector->alias('GTheatre\Template\FrontendRenderer', 'GTheatre\Template\FrontendTwigRenderer');

$injector->alias('GTheatre\Database\DatabaseProvider', 'GTheatre\Database\DBConnection');
$injector->share('GTheatre\Database\DBConnection');

return $injector;