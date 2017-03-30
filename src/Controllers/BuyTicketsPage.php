<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DatabaseProvider;
use GTheatre\Session\SessionWrapper;
use GTheatre\Exceptions\EntityExistsException;
use GTheatre\Exceptions\SQLException;

class BuyTicketsPage {
   private $request;
   private $response;
   private $renderer;
   private $dbProvider;
   private $session;

   private $templateDir = 'BuyTickets';

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

   public function show()
   {
	$title = $this->request->getParameter('Title');
	$hnumber = $this->request->getParameter('HNumber');
	$stime = $this->request->getParameter('STime');
	$tprice = $this->request->getParameter('TPrice');
    $ryear = $this->request->getParameter('RYear');
    $accType = $this->session->getValue('accType');

    $this->session->setValue('movie-title', $title);
    $this->session->setValue('movie-ryear', $ryear);
    $this->session->setValue('movie-hnumber', $hnumber);
    $this->session->setValue('movie-stime', $stime);
    $this->session->setValue('movie-tprice', $tprice);

    if ($accType == 'Customer' ) {
      $creditCard = $this->session->getValue('creditCard');
      $name = $this->session->getValue('firstName');
    
      $data = [
        'name' => $name,
        'creditCard' =>$creditCard,
		    'title' => $title,
		    'hnumber' => $hnumber,
		    'stime' => $stime,
		    'tprice' => $tprice,
        'ryear' =>$ryear
	   ];
    } else if ($accType == 'Employee') {
      $data = [
		    'title' => $title,
		    'hnumber' => $hnumber,
		    'stime' => $stime,
		    'tprice' => $tprice,
        'ryear' => $ryear
	    ];
    }
   
      $html = $this->renderer->render($this->templateDir, 'BuyTickets', $data);
      $this->response->setContent($html);
   }
   
   public function createBundle() {
      $title = $this->session->getValue('movie-title');
      $ryear = $this->session->getValue('movie-ryear');
      $ftype = $this->request->getParameter('food');
      $qty = $this->request->getParameter('qty');
      $stime = $this->session->getValue('movie-stime');
      $tprice = $this->session->getValue('movie-tprice');
	  $ticketno = $this->request->getParameter('ticketno');

      $newTicket = self::createTicket($title, $ryear, $qty, $stime, $tprice);

      $queryStr = "INSERT INTO Bundle values('$ftype', '$title', '$ryear')";
      $queryResult = $this->dbProvider->insertQuery($queryStr);

      if (!$queryResult){
         throw new SQLException("Failed to create bundle with $ftype, $title, $ryear ");
      }
   }
   
    public function createTicket($title, $ryear, $qty, $stime, $tprice) {
	  $ticketno = $this->request->getParameter('ticketno');
	  $creditCard = $this->session->getValue('creditCard');
	  $login = $this->session->getValue('userName');
	  
      $queryStr = "INSERT INTO Associated_Tickets values('$title', '$ryear', '$ticketno', '$qty', '$creditCard', '$login', '$tprice', '$stime')";
      $queryResult = $this->dbProvider->insertQuery($queryStr);

      if (!$queryResult){
         throw new SQLException("Failed to create ticket with $title, $ryear, $ticketno");
      } else return $queryResult;
   }
}
