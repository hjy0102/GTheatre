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

class Homepage
{
    private $request;
    private $response;
    private $renderer;
    private $dbProvider;
    private $session;

    private $templateDir = '';

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

    function debug_to_console( $data ) {
        $output = $data;
        if ( is_array( $output ) )
            $output = implode( ',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

    public function show()
    {
        $accType = $this->session->getValue('accType');

        $top5movies = self::getTop5();
        
        if (is_null($accType)) {
            $data = [
                'name' => $this->request->getParameter('name', 'stranger'),
                'movies' => $top5movies
            ];
        }
        else {

            $data = [
                'name' => $this->session->getValue('name'),
                'userName' => $this->session->getValue('userName'),
                'movies' => $top5movies
            ];
        }

       $html = $this->renderer->render($this->templateDir, 'HomePage', $data);
       $this->response->setContent($html);
    }

    public function getTop5() {
        $queryStr = "SELECT Title, sum(Qty) as Total FROM associated_tickets 
                    GROUP BY Title ORDER BY sum(Qty) DESC LIMIT 5";

        $top5 = $this->dbProvider->selectQuery($queryStr);

        $rows = array(); 
        
        while ($obj = $top5->fetch_object()) {
            $rows[] = $obj;
        }

        return $rows;

    }

    public function signout(){
       session_destroy();
    }


}