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
      $data = ["name" => $this->session->getValue("name")]; //hack to simulate extending layout
      $html = $this->renderer->render($this->templateDir, "ShowtimesPage", $data);
      $this->response->setContent($html);
    }

    public function getAccType() {
      echo($this->session->getValue("accType"));
    }

    public function deleteMovie() {
      $movie = $this->request->getParameter("Title");
      $year = $this->request->getParameter("RYear");
      $query = "DELETE FROM Movies WHERE Title = '$movie' AND RYear = '$year'";
      $this->dbProvider->deleteQuery($query);
      echo($query);
    }

    public function updateMovie() {
      $Title = $this->request->getParameter("Title"); 
      $RYear = $this->request->getParameter("RYear");
      $MRating = $this->request->getParameter("MRating");
      $Length = $this->request->getParameter("Length");
      $keyTitle = $this->request->getParameter("keyTitle");
      $keyRYear = $this->request->getParameter("keyRYear");

      $query = "UPDATE Movies
                SET Title = '$Title', RYear = '$RYear', MRating = '$MRating', Length = '$Length'
                WHERE Title = '$keyTitle' AND RYear = '$keyRYear';";
      
      $updated = $this->dbProvider->updateQuery($query);
      if (!$updated) {
        throw new SQLException("Failed to update movie with $Title, $RYear");
      } 
    }

    public function addMovie() {
      $Title = $this->request->getParameter("Title");
      $RYear = $this->request->getParameter("RYear");
      $MRating = $this->request->getParameter("MRating");
      $Length = $this->request->getParameter("Length");
      $HNumber = $this->request->getParameter("HNumber");
      $STimes = $this->request->getParameter("STimes");

      $movieQuery = "INSERT INTO Movies values('$Title', '$RYear', '$MRating', '$Length', 9);";
      $this->dbProvider->insertQuery($movieQuery);

      for ($i = 0; $i < count($STimes); $i++) {
        $start = $STimes[$i]["STime"];
        $end = $STimes[$i]["ETime"];
        $playsQuery = "INSERT INTO Plays values('$start', '$end', '$HNumber', '$Title', '$RYear');";
        $this->dbProvider->insertQuery($playsQuery);
      }
      // echo(json_encode($temp, JSON_NUMERIC_CHECK));
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