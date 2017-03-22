<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DatabaseProvider;
use GTheatre\Session\SessionWrapper;

class ShowtimesPage
{
    private $request;
    private $response;
    private $renderer;

    private $templateDir = 'Showtimes';

    public function __construct(
      Request $request, 
      Response $response, 
      FrontendRenderer $renderer,
      DatabaseProvider $dbProvider,
      SessionWrapper $session) {
      $this->request = $request;
      $this->response = $response;
      $this->renderer = $renderer;
      $this->dbProvider = $dbProvider;
      $this->session = $session;
    }

    public function show() {
      $html = $this->renderer->render($this->templateDir, 'ShowtimesPage');
      $this->response->setContent($html);
    }

    public function populate() {
      $queryStr_movies = "SELECT Title FROM Movies ORDER BY Title";
      $result = $this->dbProvider->selectQuery($queryStr_movies);
      while ($obj = $result->fetch_object()) {
        // echo("hi");
        echo(json_encode($obj));
      }
    }

}