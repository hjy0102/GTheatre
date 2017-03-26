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
      $query = "SELECT m.Title, m.RYear, MRating, Length, TPrice, STime, HNumber 
                FROM Movies m, Plays p
                WHERE m.Title = p.Title AND m.RYear = p.RYear
                ORDER BY m.Title, STime;";
      $result = $this->dbProvider->selectQuery($query);
      $rows = array(); 
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      echo(json_encode($rows, JSON_NUMERIC_CHECK));
    }

    public function populateHalls() {
      $query = "SELECT * FROM theatrehalls ORDER BY HNumber";
      $result = $this->dbProvider->selectQuery($query);
      $rows = array(); 
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      echo(json_encode($rows, JSON_NUMERIC_CHECK));
    }

    public function filter() {
      $movie = $this->request->getParameter("movie");
      $rating = $this->request->getParameter("rating");
      $year = $this->request->getParameter("year");

      $query = "SELECT m.Title, m.RYear, MRating, Length, TPrice, STime, HNumber FROM Movies m, Plays p WHERE m.Title = p.Title AND m.RYear = p.RYear";
      
      if ($movie != "") {
        $query = $query . " AND m.Title = '$movie'";
      }

      if (count($rating) > 0) {
        $query = $query . " AND (";
        for ($i = 0; $i < count($rating) - 1; $i++) {
          $query = $query . "m.MRating = '$rating[$i]' OR ";
        }
        $last = count($rating) - 1;
        $query = $query . "m.MRating = '$rating[$last]')";
      }

      if (count($year) > 0) {
        $query = $query . " AND m.RYear >= $year[0] AND m.RYear <= $year[1]";
      }

      $query = $query . " ORDER BY m.Title, STime;";
      // echo($query); //debug to see 
      $result = $this->dbProvider->selectQuery($query);

      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      echo(json_encode($rows, JSON_NUMERIC_CHECK));
    }

}