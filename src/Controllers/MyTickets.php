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

class MyTickets
{
   private $request;
   private $response;
   private $renderer;
   private $dbProvider;
   private $session;

   private $templateDir = 'Account';

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
       $creditCard = $this->session->getValue('creditCard');
       $name = $this->session->getValue('name');

       $movies = self::getMovieTickets($username, $creditCard);
    //    var_dump($movies);

        $data = [
            'username' => $username,
            'creditCard' => $creditCard,
            'name' => $name,
            'movies' => $movies
        ];

       // var_dump($data);

       $html = $this->renderer->render($this->templateDir, 'MyTickets', $data);
       
       $this->response->setContent($html);
    }
    
    public function getMovieTickets($u, $credit) {
        $queryStr = "SELECT * FROM Plays p JOIN Associated_Tickets t ". 
                        " WHERE t.CreditCard = '$credit'". 
                        " AND t.Login = '$u' 
                        AND t.Title = p.Title
                        AND t.STime = p.STime";

        $ticketArr = $this->dbProvider->selectQuery($queryStr);

        $rows = array(); 
        
        while ($obj = $ticketArr->fetch_object()) {
            $rows[] = $obj;
        }

        return $rows;

        //return $ticketArr;
    }



}


?>