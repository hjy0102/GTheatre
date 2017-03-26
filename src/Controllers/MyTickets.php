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
       
       $data = self::getMovieTickets($username, $creditCard);
       $html = $this->renderer->render($this->templateDir, 'MyTickets', $data);
       
       $this->response->setContent($html);
    }
    
    public function getMovieTickets($u, $credit) {
        $queryStr = "SELECT * FROM Associated_Tickets ". 
                        " WHERE CreditCard = '$credit'". 
                        " AND Login = '$u'";

        $ticketArr = $this->dbProvider->selectQuery($queryStr);

        $rows = array(); 
        
        while ($obj = $ticketArr->fetch_object()) {
            $rows[] = $obj;
        }

        return $rows;
    }



}


?>