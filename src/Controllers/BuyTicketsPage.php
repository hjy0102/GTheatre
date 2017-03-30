<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DatabaseProvider;
use GTheatre\Session\SessionWrapper;

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
      $title = $this->request->getParameter('title');
      $ryear = $this->request->getParameter('ryear');
      $ftype = $this->request->getParameter('fType');
      $qty = $this->request->getParameter('qty');
	  $ticketno = $this->request->getParameter('ticketno');

      $queryStr = "INSERT INTO Bundle values('$ftype', '$title', '$ryear', '$ticketno')";
      $this->dbProvider->insertQuery($queryStr);
   }
   
    public function createTicket() {
      $title = $this->request->getParameter('title');
      $ryear = $this->request->getParameter('ryear');
      $qty = $this->request->getParameter('qty');
	  $stime = $this->request->getParameter('STime');
	  $ticketno = $this->request->getParameter('ticketno');
	  $tprice = $this->request->getParameter('tprice');
	  $creditCard = $this->session->getValue('creditCard');
	  $login = $this->session->getValue('login-username');
	  
      $queryStr = "INSERT INTO Associated_Tickets values('$title', '$ryear', '$ticketno', '$qty', '$creditCard', '$login', '$tprice', '$stime')";
      $this->dbProvider->insertQuery($queryStr);
   }
}
