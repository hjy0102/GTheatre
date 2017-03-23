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

    public function populateMovies() {
      $queryStr_movies = "SELECT * FROM movies ORDER BY Title";
      $result = $this->dbProvider->selectQuery($queryStr_movies);
      $rows = array(); 
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      echo(json_encode($rows));
    }

    public function populateHalls() {
      $queryStr_movies = "SELECT * FROM theatrehalls ORDER BY HNumber";
      $result = $this->dbProvider->selectQuery($queryStr_movies);
      $rows = array(); 
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      echo(json_encode($rows));
    }

}