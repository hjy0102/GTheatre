<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');

$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);
$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->alias('GTheatre\Template\Renderer', 'GTheatre\Template\TwigRenderer');
$injector->delegate('Twig_Environment', function () use ($injector) {
   $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
   $twig = new Twig_Environment($loader);
   return $twig;
});

$injector->alias('GTheatre\Template\FrontendRenderer', 'GTheatre\Template\FrontendTwigRenderer');

$injector->alias('GTheatre\Menu\MenuReader', 'GTheatre\Menu\ArrayMenuReader');
$injector->share('GTheatre\Menu\ArrayMenuReader');

$injector->alias('GTheatre\Database\DatabaseProvider', 'GTheatre\Database\MySQLDatabaseProvider');
$injector->share('GTheatre\Database\MySQLDatabaseProvider');

$injector->alias('GTheatre\Session\SessionWrapper', 'GTheatre\Session\PHPSessionWrapper');

return $injector;