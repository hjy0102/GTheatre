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
        $QueryStr = "SELECT * FROM Employees WHERE Employee_Login = '$u' ";

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

   public function update()
   {
      $name = $this->request->getParameter('account-name');
      $phone = $this->request->getParameter('account-phone');
      $address = $this->request->getParameter('account-address');

      $username = $this->session->getValue('userName');
      $accType = $this->session->getValue('accType');

      if (is_null($accType)) {
         header('Location: /');
         exit();
      }

      if (is_null($name) || strlen($name) == 0) {
         throw new InvalidArgumentException("required form input missing. name.");
      }

      $validateQueryStr = "SELECT * FROM Users " .
                          "WHERE userName = '$username' AND u_deleted = 'F'";
      $validateResult = $this->dbProvider->selectQuery($validateQueryStr);

      if(!empty($validateResult)) {
         $updateQueryStr = "UPDATE Users " .
                           "SET name = '$name', phone = '$phone', address = '$address' " .
                           "WHERE userName = '$username'";

         $updated = $this->dbProvider->updateQuery($updateQueryStr);

         if (!$updated) {
            throw new SQLException("Failed to update User with $name, $phone, $address");
         }
      }
      else {
         throw new MissingEntityException("Unable to find User $username to update");
      }
   }

   public function showAllChefAccounts()
   {
      $accType = $this->session->getValue('accType');
      if (is_null($accType) || strcasecmp($accType, 'admin') != 0) {
         header('Location: /');
         exit();
      }

      $chefQueryStr = "SELECT userName, name, phone, address, employee_id, ssNum FROM Users " . 
                      "INNER JOIN Chef " .
                      "ON Users.userName = Chef.chef_userName " .
                      "WHERE type = 'chef' AND u_deleted = 'F'";
      $chefResult = $this->dbProvider->selectMultipleRowsQuery($chefQueryStr);

      $data = [
         'chefs' => $chefResult
      ];

      $html = $this->renderer->render($this->templateDir, 'ManageChefpage', $data);
      $this->response->setContent($html);
   }

   public function showCreateChefForm()
   {
      $accType = $this->session->getValue('accType');
      if (is_null($accType) || strcasecmp($accType, 'admin') != 0) {
         header('Location: /');
         exit();
      }

      $data = [];
      
      $html = $this->renderer->render($this->templateDir, 'CreateChefFormpage', $data);
      $this->response->setContent($html);      
   }

   public function createChefAccount()
   {
      $name = $this->request->getParameter('chef-name');
      $username = $this->request->getParameter('chef-username');
      $password = $this->request->getParameter('chef-password');
      $phone = $this->request->getParameter('chef-phone');
      $address = $this->request->getParameter('chef-address');
      $ssNum = $this->request->getParameter('chef-ssNum');

      $currentUsername = $this->session->getValue('userName');

      $currentAccType = $this->session->getValue('accType');
      if (is_null($currentAccType) || strcasecmp($currentAccType, 'admin') != 0) {
         header('Location: /');
         exit();
      }

      if (is_null($name) || strlen($name) == 0 ||
          is_null($username) || strlen($username) == 0 ||
          is_null($password) || strlen($password) == 0) {
         throw new InvalidArgumentException("required form input missing. Either name, username, or password.");
      }

      if (!(is_null($ssNum) || strlen($ssNum) == 0 || is_numeric($ssNum))) {
         throw new InvalidArgumentException("social security number is invalid.");
      }


      $usernameUserQueryStr = "SELECT * FROM Users WHERE userName = '$username'";
      $usernameUserQueryResult = $this->dbProvider->selectQuery($usernameUserQueryStr);

      $usernameChefQueryStr = "SELECT * FROM Chef WHERE chef_userName = '$username'";
      $usernameChefQueryResult = $this->dbProvider->selectQuery($usernameChefQueryStr);

      if (!empty($usernameUserQueryResult) || !empty($usernameChefQueryResult)) {
         throw new EntityExistsException("User or Chef exists with username $username");
      }

      $insertUserQueryStr = "INSERT INTO Users " .
                            "(userName, password, type, name, phone, address, createDate, u_deleted) " .
                            "VALUE " .
                            "('$username', '$password', 'chef', '$name', '$phone', '$address', now(), 'F')";


      if (is_null($ssNum) || strlen($ssNum) == 0) {
         $insertChefQueryStr = "INSERT INTO Chef " .
                               "(chef_userName, admin_userName) " .
                               "VALUE " .
                               "('$username', '$currentUsername')";
      }
      else {
         $insertChefQueryStr = "INSERT INTO Chef " .
                               "(chef_userName, admin_userName, ssNum) " .
                               "VALUE " .
                               "('$username', '$currentUsername', '$ssNum')";
      }


      $queryArr = [
         1 => $insertUserQueryStr,
         2 => $insertChefQueryStr
      ];
      $queryResult = $this->dbProvider->applyQueries($queryArr);

      if (!$queryResult) {
         throw new SQLException("Failed to insert User and Chef");
      }
   }

   public function showEditChefForm($routeParams)
   {
      $username = $routeParams['username'];

      $currentAccType = $this->session->getValue('accType');
      if (is_null($currentAccType) || strcasecmp($currentAccType, 'admin') != 0) {
         header('Location: /');
         exit();
      }

      $chefQueryStr = "SELECT userName, name, phone, address, employee_id, ssNum FROM Users " .
                      "INNER JOIN Chef " .
                      "ON Users.userName = Chef.chef_userName " .
                      "WHERE userName = '$username' AND type = 'chef' AND u_deleted = 'F'";
      $chefResult = $this->dbProvider->selectQuery($chefQueryStr);

      if (empty($chefResult)) {
         throw new MissingEntityException('Unable to find chef information');
      }

      $data = [
         'name' => $chefResult["name"],
         'userName' => $chefResult["userName"],
         'phone' => $chefResult["phone"],
         'address' => $chefResult["address"],
         'employee_id' => $chefResult["employee_id"],
         'ssNum' => $chefResult["ssNum"]
      ];

      $html = $this->renderer->render($this->templateDir, 'EditChefFormpage', $data);
      $this->response->setContent($html);
   }


   public function updateChefAccount($routeParams)
   {
      $name = $this->request->getParameter('chef-name');
      $username = $this->request->getParameter('chef-username');
      $phone = $this->request->getParameter('chef-phone');
      $address = $this->request->getParameter('chef-address');
      $employee_id = $this->request->getParameter('chef-employee-id');
      $ssNum = $this->request->getParameter('chef-ssNum');

      $currentUsername = $this->session->getValue('userName');
      $currentAccType = $this->session->getValue('accType');

      if (is_null($currentAccType) || strcasecmp($currentAccType, 'admin') != 0) {
         header('Location: /');
         exit();
      }

      if (is_null($name) || strlen($name) == 0 ||
          is_null($username) || strlen($username) == 0) {
         throw new InvalidArgumentException("required form input missing. Either name, or username.");
      }

      if (is_null($employee_id) || !is_numeric($employee_id)) {
         throw new InvalidArgumentException("employee id is invalid");
      }

      if (!(is_null($ssNum) || strlen($ssNum) == 0 || is_numeric($ssNum))) {
         throw new InvalidArgumentException("social security number is invalid.");
      }


      $employeeIdQueryStr = "SELECT * FROM Chef " .
                            "WHERE employee_id = $employee_id AND chef_userName != '$username'";
      $employeeIdQueryResult = $this->dbProvider->selectQuery($employeeIdQueryStr);

      if (!empty($employeeIdQueryResult)) {
         throw new EntityExistsException("Employee id is taken by another chef");
      }

      if (!is_null($ssNum) && strlen($ssNum) != 0) {
         $ssNumQueryStr = "SELECT * FROM Chef " .
                          "WHERE ssNum = $ssNum AND chef_userName != '$username'";
         $ssNumQueryResult = $this->dbProvider->selectQuery($ssNumQueryStr);

         if (!empty($ssNumQueryResult)) {
            throw new EntityExistsException("Social security number is taken by another chef");
         }
      }

      $validateUserQueryStr = "SELECT * FROM Users " .
                              "WHERE userName = '$username' AND type = 'chef' AND u_deleted = 'F'";
      $validateUserResult = $this->dbProvider->selectQuery($validateUserQueryStr);

      $validateChefQueryStr = "SELECT * FROM Chef " .
                              "WHERE chef_userName = '$username'";
      $validateChefResult = $this->dbProvider->selectQuery($validateChefQueryStr);

      if (!empty($validateUserResult) && !empty($validateChefResult)) {
         $updateUserQueryStr = "UPDATE Users " .
                               "SET name = '$name', phone = '$phone', address = '$address' " .
                               "WHERE userName = '$username' AND type = 'chef'";

         if (is_null($ssNum) || strlen($ssNum) == 0) {
            $updateChefQueryStr = "UPDATE Chef " .
                                  "SET employee_id = $employee_id, ssNum = NULL " .
                                  "WHERE chef_userName = '$username'";
         }
         else {
            $updateChefQueryStr = "UPDATE Chef " .
                                  "SET employee_id = $employee_id, ssNum = $ssNum " .
                                  "WHERE chef_userName = '$username'";
         }

         $queryArr = [
            1 => $updateUserQueryStr,
            2 => $updateChefQueryStr
         ];
         $queryResult = $this->dbProvider->applyQueries($queryArr);

         if (!$queryResult) {
            throw new SQLException("Failed to update User and Chef");
         }
      }
      else {
         throw new MissingEntityException("Unable to find either User or Chef $username to update");
      }
   }

   public function deleteChefAccount($routeParams)
   {
      $username = $this->request->getParameter('chef-username');

      $accType = $this->session->getValue('accType');
      if (is_null($accType) || strcasecmp($accType, 'admin') != 0) {
         throw new PermissionException("Must be admin in order to delete chef account");
      }

      if (is_null($username) || strlen($username) == 0) {
         throw new InvalidArgumentException("Username missing.");
      }

      $validateUserQueryStr = "SELECT * FROM Users " .
                              "WHERE userName = '$username' AND type = 'chef' AND u_deleted = 'F'";
      $validateUserResult = $this->dbProvider->selectQuery($validateUserQueryStr);

      $validateChefQueryStr = "SELECT * FROM Chef " .
                              "WHERE chef_userName = '$username'";
      $validateChefResult = $this->dbProvider->selectQuery($validateChefQueryStr);

      $validateNoOpenOrder = "SELECT * FROM Orders " .
                             "WHERE chef_userName = '$username' " .
                             "AND cookedStatus <> 'cooked'";
      $validateNoOpenResult = $this->dbProvider->selectQuery($validateNoOpenOrder);

      if (!empty($validateUserResult) && !empty($validateChefResult) && empty($validateNoOpenResult)) {
         $deleteQuery = "DELETE FROM Users " .
                        "WHERE userName = '$username' AND type = 'chef'";
         $deleteResult = $this->dbProvider->updateQuery($deleteQuery);

         if (!$deleteResult) {
            throw new SQLException("Failed to delete Chefs account");
         }

      }
      else {
         throw new MissingEntityException("Unable to find User or Chef $username to delete");
      }
   }
}