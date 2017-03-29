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

	  $data = [
		'title' => $title,
		'hnumber' => $hnumber,
		'stime' => $stime,
		'tprice' => $tprice
	  ];
   
      $html = $this->renderer->render($this->templateDir, 'BuyTickets', $data);
      $this->response->setContent($html);
   }
   
   public function createBundle()
   {
	$title = $this->request->getParameter('Title');
	$ryear = $this->request->getParameter('RYear');
	$queryStr = "INSERT INTO Bundle values('null', '$title', '$ryear', 'null')";
	$this->dbProvider->insertQuery($queryStr);
   }
}
