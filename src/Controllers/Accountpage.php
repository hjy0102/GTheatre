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

class Accountpage
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
      $accType = $this->session->getValue('accType');
      $username = $this->session->getValue('userName');
      if (is_null($accType)) {
         header('Location: /');
         exit();
      }

      if ($accType == 'Customer') {
          $cdata = self::customerShow($username);
          $html = $this->renderer->render($this->templateDir, 'Accountpage', $cdata);
          $this->response->setContent($html);

      } else if ($accType == 'Employee') {
          $edata = self::employeeShow($username);
          $html = $this->renderer->render($this->templateDir, 'Accountpage', $edata);
          $this->response->setContent($html);
      }
   }

   public function customerShow($u){
        $queryStr = "SELECT * FROM Customers WHERE Customer_Login = '$u' ";

        $userResult = $this->dbProvider->selectQuery($queryStr);

        if (!$userResult) {
            throw new SQLException('Unable to find user with that username');
        }

        if ($userResult->num_rows === 0) {
            throw new EntityExistsException('Unable to find current user information');
        } 

        while ($obj = $userResult->fetch_object()) {
            $data = [
                'accType' => 'Customer',
                'name' => $obj->FirstName,
                'creditCard' => $obj->CreditCard,
                'currectPassword' => $obj->Customer_Password
                ];
        };

        return $data;


   }

   public function employeeShow($u) {
        $queryStr = "SELECT * FROM Employees WHERE Employee_Login = '$u' ";

        $userResult = $this->dbProvider->selectQuery($queryStr);

        if (!$userResult) {
            throw new SQLException('Unable to find user with that username');
        }

        if ($userResult->num_rows === 0) {
            throw new EntityExistsException('Unable to find current user information');
        } 

        while ($obj = $userResult->fetch_object()) {
            $data = [
                'accType' => 'Employee',
                'name' => $obj->FirstName,
                'sinno' => $obj->SIN,
                'currectPassword' => $obj->Employee_Password
                ];
        };

        return $data;
   }

   public function updateCustomer()
   {
      $name = $this->request->getParameter('account-name');
      $password = $this->request->getParameter('account-pwd');
      $creditCard = $this->request->getParameter('account-creditCard');

      $username = $this->session->getValue('userName');
      $accType = $this->session->getValue('accType');

      if (is_null($accType)) {
         header('Location: /');
         exit();
      }

      if (is_null($name) || strlen($name) == 0) {
         throw new InvalidArgumentException("required form input missing. name.");
      }

      $validateQueryStr = "SELECT * FROM Customers " .
                          "WHERE Customer_Login = '$username'";
      $validateResult = $this->dbProvider->selectQuery($validateQueryStr);

      if($validateResult->num_rows == 1) {
         $updateQueryStr = "UPDATE Customers " .
                           "SET FirstName = '$name', Customer_Password = '$password', CreditCard = '$creditCard' " .
                           "WHERE Customer_Login = '$username'";

         $updated = $this->dbProvider->updateQuery($updateQueryStr);

         if (!$updated) {
            throw new SQLException("Failed to update User with $name, $password, $creditCard");
         }
      }
      else {
         throw new MissingEntityException("Unable to find Customer $username to update");
      }
   }


   public function updateEmployee()
   {
      $name = $this->request->getParameter('account-name');
      $password = $this->request->getParameter('account-pwd');
      $sin = $this->request->getParameter('account-sinno');

      $username = $this->session->getValue('userName');
      $accType = $this->session->getValue('accType');

      if (is_null($accType)) {
         header('Location: /');
         exit();
      }

      if (is_null($name) || strlen($name) == 0) {
         throw new InvalidArgumentException("required form input missing. name.");
      }

      $validateQueryStr = "SELECT * FROM Employees " .
                          "WHERE Employee_Login = '$username'";
      $validateResult = $this->dbProvider->selectQuery($validateQueryStr);

      if($validateResult->num_rows == 1) {
         $updateQueryStr = "UPDATE Customers " .
                           "SET FirstName = '$name', Customer_Password = '$password', SIN = '$sin' " .
                           "WHERE Employee_Login = '$username'";

         $updated = $this->dbProvider->updateQuery($updateQueryStr);

         if (!$updated) {
            throw new SQLException("Failed to update Employee with $name, $password, $sin");
         }
      }
      else {
         throw new MissingEntityException("Unable to find Employee $username to update");
      }
   }
   
}