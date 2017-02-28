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
      // queryStr is used for finding customers
      $queryStr = "SELECT * FROM Customers " .
                  "WHERE Login = '$login' AND " .
                  "password = '$password' " ;
    // queryStr_2 is used for finding employees
      $queryStr_2 =  "SELECT * FROM Employees" . 
                    "WHERE Login = '$login' AND " .
                  "password = '$password' " ;
        // TODO front end should have some sort of indication of "I'm a customer or employee"
        // !!!
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
   // TODO !!! frontend form for creating an account 
   // Potentially might need two create Account functions
   public function createAccount()
   {
      // if want to be really careful. limit character size on inputs..
      // needs disclaimer about password being saved as plaintext
      $firstname = $this->request->getParameter('reg-FirstName');
      $login = $this->request->getParameter('reg-login');
      $password = $this->request->getParameter('reg-password');

      $creditCard = $this->request->getParameter('reg-creditCard');
      $sinno = $this->request->getParameter('reg-sin');

      if (is_null($firstname) || strlen($firstname) == 0 ||
          is_null($login) || strlen($login) == 0 ||
          is_null($password) || strlen($password) == 0) {
         throw new InvalidArgumentException("required form input missing. Either name, username, or password.");
      }


      $customerQueryStr = "SELECT * FROM Customers WHERE Login = '$login'";
      $customerQueryResult = $this->dbProvider->selectQuery($customerQueryStr);

      $employeeQueryStr = "SELECT * FROM Employees WHERE Login = '$login'";
      $employeeQueryResult = $this->dbProvider->selectQuery($employeeQueryStr);


      if (!empty($customerQueryResult) || !empty($employeeQueryResult)) {
         throw new EntityExistsException("User exists with username $username");
      }

      $registerCustomerQueryStr = "INSERT INTO Customers " .
                          "(CreditCard, Login, Password, FirstName) " .
                          "VALUE " .
                          "('$creditCard', '$login', '$password', '$firstname'";
        $registerEmployeeQueryStr = "INSERT INTO Employees " .
                          "(SIN, Login, Password, FirstName) " .
                          "VALUE " .
                          "('$sinno', '$login', '$password', '$firstname'";

// NOTE: default is everyone is a customer! 
// check to see if a creditcard was provided then if a SIN number was provided
// if both provided the user will be registered as a customer
      if (is_null($creditCard) || strlen($creditCard) == 0) {
          //no credit card provided
          if (is_null($sinno) || strlen($sinno) == 0 ) {
              // no valid SIN number is provided
              throw new InvalidArgumentException("please provide a valid Credit Card to be registered as a customer or a valid SIN number to be registered as an employee");
          } else {
              // input is valid SIN and no credit card
              // to be registered as an Employee
              // TODO !!!
            $created = $this->dbProvider->insertQuery($registerEmployeeQueryStr);

          }
          // valid credit card was provided !!!
      } else {
          // to be registered as a Customers
          // TODO !!!
        $created = $this->dbProvider->insertQuery($registerCustomerQueryStr);
      }

      if (!$created) {
         throw new SQLException("Failed to create User with $name, $username, $password");
      }
   }

}