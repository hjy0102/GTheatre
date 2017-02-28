<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DBConnection;
use GTheatre\Session\SessionWrapper;
use GTheatre\Exceptions\EntityExistsException;
use GTheatre\Exceptions\SQLException;
use \InvalidArgumentException;

class Loginpage
{
   private $request;
   private $response;
   private $renderer;
   private $dbProvider;
   private $session;

   private $templateDir = 'Login';

   public function __construct(
      Request $request,
      Response $response,
      FrontendRenderer $renderer,
      DBConnection $dbProvider,
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
      $html = $this->renderer->render($this->templateDir, 'Loginpage');
      $this->response->setContent($html);
   }
//TODO frontend create form
// temporary sets account type 
// TODO fix later !!!
   public function login()
   {
      $login = $this->request->getParameter('login-login');
      $password = $this->request->getParameter('login-password');
      $accType = $this->request->getParameter('login-acc-type');

      if (is_null($login) || strlen($login) == 0 ||
          is_null($password) || strlen($password) == 0 ||
          is_null($accType) || strlen($accType) == 0) {
         throw new InvalidArgumentException('required form input missing. Either username, password, or accType.');
      }

      

      $queryStr = "SELECT * FROM Customers " .
                  "WHERE Login = '$login' AND " .
                  "password = '$password' " ;
      $queryStr_2 =  "SELECT * FROM Employees" . 
                    "WHERE Login = '$login' AND " .
                  "password = '$password' " ;
        if ($accType == "Customer") {
            $result = $this->dbProvider->selectQuery($queryStr);
        } else {
            $result = $this->dbProvider->selectQuery($queryStr_2);
        }

      if (empty($result)) {
         throw new InvalidArgumentException('invalid credentials provided.');
      }
      else {
         $this->session->setValue('name', $result["FirstName"]);
         $this->session->setValue('login', $result["Login"]);
      }
   }


}