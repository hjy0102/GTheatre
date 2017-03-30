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
      $query = self::buildQuery();
      $rows = self::query($query);
      $data = [
        'movies' => self::parseMovies($rows),
        'ratings' => self::parseRatings($rows),
        'years' => self::parseYears($rows),
        'halls' => self::populateHalls()
      ];
      // var_dump($data);
      $html = $this->renderer->render($this->templateDir, 'ShowtimesPage', $data);
      $this->response->setContent($html);
    }

    private function parseRatings($rows) {
      $ratings = array();
      foreach($rows as $r) {
        if(!in_array($r->MRating, $ratings)) {
          array_push($ratings, $r->MRating);
        }
      }
      return $ratings;
    }

    private function parseYears($rows) {
      $min = $rows[0]->RYear;
      $max = $rows[0]->RYear;
      foreach ($rows as $r) {
        if ($r->RYear < $min) {
          $min = $r->RYear;
        }
        if ($r->RYear > $max) {
          $max = $r->RYear;
        }
      }
      return ['Min' => $min, 'Max' => $max];
    }

    private function parseMovies($rows) {
      $movies = [];
      $dupes = array();
      foreach ($rows as $row) {
          if (!in_array($row->Title, $dupes)) {
              $row->STime = array(self::removeSeconds($row->STime));
              array_push($movies, $row);
              array_push($dupes, $row->Title);
          } else {
              foreach ($movies as $movie) {
                  if ($row->Title == $movie->Title) {
                      array_push($movie->STime, self::removeSeconds($row->STime));
                  }
              }
          }
      }
      return $movies;
    }

    private function removeSeconds($time) {
      return explode(":", $time)[0] . ":" . explode(":", $time)[1];
    }

    private function populateHalls() {
      $query = "SELECT * FROM theatrehalls ORDER BY HNumber";
      $result = $this->dbProvider->selectQuery($query);
      $rows = array(); 
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      return $rows;
    }


    public function filter() {
      $query = self::buildQuery();
      $rows = self::query($query);
      $data = [
        'movies' => self::parseMovies($rows),
        'ratings' => self::parseRatings($rows),
        'years' => self::parseYears($rows),
        'halls' => self::populateHalls()
      ];
      echo(json_encode($data, JSON_NUMERIC_CHECK));
      // $html = $this->renderer->render($this->templateDir, 'ShowtimesPage', $data);
      // $this->response->setContent($html);
    }

    private function buildQuery() {
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

      return $query;
    }

    private function query($query) {
      $result = $this->dbProvider->selectQuery($query);
      while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
      }
      return $rows;
    }

}