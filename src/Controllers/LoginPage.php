<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DatabaseProvider;
use GTheatre\Session\SessionWrapper;
use GTheatre\Exceptions\EntityExistsException;
use GTheatre\Exceptions\SQLException;
use \InvalidArgumentException;

class Loginpage {
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
      $html = $this->renderer->render($this->templateDir, 'Loginpage');
      $this->response->setContent($html);
   }

   public function login(){

        $username = $this->request->getParameter('login-username');
        $password = $this->request->getParameter('login-password');
        $accType = $this->request->getParameter('login-acc-type');
        
        if (is_null($username) || strlen($username) == 0 ||
            is_null($password) || strlen($password) == 0 ||
            is_null($accType) || strlen($accType) == 0) {
                throw new InvalidArgumentException('Required form input missing. Either username, password, or accType.');
        }

        $queryStr_customer = "SELECT * FROM Customers " .
                  "WHERE Customer_Login = '$username' AND " .
                  "Customer_Password = '$password' ";

        $queryStr_employee = "SELECT * FROM Employees ". 
                            "WHERE Employee_Login = '$username' AND".
                            "Employee_Password = '$password'";
      

        if ($accType == 'Customer') {
            $result = $this->dbProvider->selectQuery($queryStr_customer);
        } else if ($accType == 'Employee') {
            $result = $this->dbProvider->selectQuery($queryStr_employee);
        }

        if (empty($result)) {
         throw new InvalidArgumentException('Invalid credentials provided.');
        }
        else if ($accType == 'Customer') {
            $this->session->setValue('accType', 'Customer');
            $this->session->setValue('name', $result["FirstName"]);
            $this->session->setValue('userName', $result["Customer_Login"]);
            $this->session->setValue('creditCard', $result["CreditCard"]);
        } else if ($accType == 'Employee') {
            $this->session->setValue('accType', 'Employee');
            $this->session->setValue('name', $result["FirstName"]);
            $this->session->setValue('userName', $result["Employee_Login"]);
            $this->session->setValue('sin', $result["SIN"]);
        }
    }
    

   public function createCustomerAccount(){
      // if want to be really careful. limit character size on inputs..
      // needs disclaimer about password being saved as plaintext
      $name = $this->request->getParameter('reg-name');
      $username = $this->request->getParameter('reg-username');
      $password = $this->request->getParameter('reg-password');
      $credit = $this->request->getParameter('reg-creditCard');
      $accType = $this->request->getParameter('reg-acc-type');

      if (is_null($name) || strlen($name) == 0 ||
          is_null($username) || strlen($username) == 0 ||
          is_null($password) || strlen($password) == 0) {
         throw new InvalidArgumentException("Required form input missing. Either name, username, or password.");
      }
        $usernameQueryStr_c = "SELECT * FROM Customers WHERE Customer_Login = '$username'";
        $usernameQueryResult = $this->dbProvider->selectQuery($usernameQueryStr_c);
      
      if (!empty($usernameQueryResult)) {
         throw new EntityExistsException("User exists with username $username");
      }

      $registerQueryStr_c = "INSERT INTO Customers " .
                          "(CreditCard, Customer_Login, Customer_Password, FirstName) " .
                          "VALUE " .
                          "('$credit', '$username', '$password', '$name') ";

      $created = $this->dbProvider->insertQuery($registerQueryStr_c);
      
      if (!$created) {
         throw new SQLException("Failed to create User with $name, $username, $password");
      }
   }
    
    public function createEmployeeAccount(){
      // if want to be really careful. limit character size on inputs..
      // needs disclaimer about password being saved as plaintext
      $name = $this->request->getParameter('reg-ename');
      $username = $this->request->getParameter('reg-eusername');
      $password = $this->request->getParameter('reg-epassword');
      $sinno = $this->request->getParameter('reg-sin');
      $accType = $this->request->getParameter('reg-acc-type');

      if (is_null($name) || strlen($name) == 0 ||
          is_null($username) || strlen($username) == 0 ||
          is_null($password) || strlen($password) == 0) {
         throw new InvalidArgumentException("Required form input missing. Either name, username, or password.");
      }
        $usernameQueryStr_e = "SELECT * FROM Employees WHERE Employee_Login = '$username' ";

        $usernameQueryResult = $this->dbProvider->selectQuery($usernameQueryStr_e);

      
      if (!empty($usernameQueryResult)) {
         throw new EntityExistsException("User exists with username $username");
      }

    $registerQueryStr_e = "INSERT INTO Employees ". 
                        "(SIN, Employee_Login, Employee_Password, FirstName) ". 
                        " VALUE ". 
                        "('$sinno, $username, $password, $name' ) ";

      $created = $this->dbProvider->insertQuery($registerQueryStr_e);
      
      if (!$created) {
         throw new SQLException("Failed to create User with $name, $username, $password");
      }
   }



}