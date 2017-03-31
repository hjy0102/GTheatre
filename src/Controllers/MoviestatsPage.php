<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DatabaseProvider;
use GTheatre\Session\SessionWrapper;
use GTheatre\Exceptions\MissingEntityException;
use GTheatre\Exceptions\EntityExistsException;
use GTheatre\Exceptions\SQLException;
use GTheatre\Exceptions\PermissionException;
use \InvalidArgumentException;

class MovieStatsPage
{
   private $request;
   private $response;
   private $renderer;
   private $dbProvider;
   private $session;

   private $templateDir = 'Showtimes';

   public function __construct(
      Request $request,
      Response $response,
      FrontendRenderer $renderer,
      DatabaseProvider $dbProvider,
      SessionWrapper $session)
   {
      $this->request = $request;
      $this->response = $response;
      $this->renderer = $renderer;
      $this->dbProvider = $dbProvider;
      $this->session = $session;
   }
   
   public function show() {
       $username = $this->session->getValue('userName');
       $accType = $this->session->getValue('accType');
       $name = $this->session->getValue('name');
       

       $customers_all_movies = self::getCustomer_AllMovies();
       $mostPop = self::getLargestGroup();
       $leastPop = self::getSmallestGroup();

        $data = [
            'allMovies' => $customers_all_movies,
            'mostPop' => $mostPop,
            'leastPop' => $leastPop,
        ];

    //    var_dump($data);

       $html = $this->renderer->render($this->templateDir, 'MoviestatsPage', $data);
       
       $this->response->setContent($html);
    }

    public function getSmallestGroup(){

        $queryStr = "SELECT min(AverageNumTicketsPerMovie.avgQty) AS min, stime, title FROM (SELECT avg(qty) AS avgQty, title ,stime
                        FROM associated_tickets GROUP BY title)AverageNumTicketsPerMovie";

        $leastPop = $this->dbProvider->selectQuery($queryStr);
        $rows =array();

        while ($obj = $leastPop->fetch_object()){
            $rows[] = $obj;
        }

        return $rows;
    }

    public function getLargestGroup(){

        $queryStr = "SELECT max(AverageNumTicketsPerMovie.avgQty) AS max, stime, title FROM (SELECT avg(qty) AS avgQty, title, stime  
                        FROM associated_tickets GROUP BY title)AverageNumTicketsPerMovie";

        $mostPop = $this->dbProvider->selectQuery($queryStr);
        $rows =array();

        while ($obj = $mostPop->fetch_object()){
            $rows[] = $obj;
        }

        return $rows;
    }

    public function getCustomer_AllMovies(){
        $queryStr = "SELECT * FROM customers c
                    WHERE NOT EXISTS (SELECT * FROM movies m
                                      WHERE NOT EXISTS (SELECT * FROM associated_tickets t
 										                WHERE t.title=m.title AND t.login=c.customer_login)
                                      )";

        $customers = $this->dbProvider->selectQuery($queryStr);
        $rows =array();

        while ($obj = $customers->fetch_object()){
            $rows[] = $obj;
        }

        return $rows;

    }



}


?>