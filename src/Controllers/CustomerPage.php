<?php declare(strict_types = 1);

namespace GTheatre\Controllers;

use Http\Request;
use Http\Response;
use GTheatre\Template\FrontendRenderer;
use GTheatre\Database\DBConnection;
use GTheatre\Session\SessionWrapper;
use GTheatre\Exceptions\MissingEntityException;
use GTheatre\Exceptions\EntityExistsException;
use GTheatre\Exceptions\SQLException;
use GTheatre\Exceptions\PermissionException;
use GTheatre\Exceptions\NoAccessException;
use \InvalidArgumentException;

class CustomerPage{

    private $request;
    private $response;
    private $renderer;
    private $dbConnection;
    private $session;

    private $templateDir = 'Customer';

    public function __construct(
        Request $request,
        Response $response,
        FrontendRenderer $renderer,
        DBConnection $dbConnection,
        SessionWrapper $session){
            $this->request = $request;
            $this->response = $response;
            $this->renderer = $renderer;
            $this->dbConnection = $dbConnection;
            $this->session = $session;
            }

// Set HTML response with appropriate Customer information
// Note: a little sketchy about the credit card information and password but
// TODO at a later time !!!

    public function show(){
        $Login = $this->session->getValue('Login');
        
        if (is_null($Login)) {
            // no input
            header('Location: /');
            exit();
        }

        // database query in SQL
        $userQueryStr = "SELECT CreditCard, Login, Password, FirstName FROM Customers"."WHERE Login = '$Login'";
        // predefined in PHP mysqli API
        $userResult = $this->dbConnection->selectQuery($userQueryStr);
        
        if (empty($userResult)) {
            // cannot find the user in the db
            throw new MissingEntityException('Unable to find current user information');
        }
        
        $data = [
            'Login' => $Login,
            'FirstName' => $userResult["FirstName"],
            'CreditCard' => $userResult["CreditCard"],
            'Password' => $userResult["Password"]
        ];
        
        $html = $this->renderer->render($this->templateDir, 'CustomerPage', $data);
        $this->response->setContent($html);

    }

// helper function
// check the number of digits a number has 
// PRE: a number 
// RETURNS integer representing the number of digits
    private function count_digit($number) {
        return strlen((string) $number);
    }

// check correct password for Customer login
// RETURNS boolean

    private function checkPW($Login, $input){
        $correctPW = FALSE;

        if (is_null($Login)){
            throw new InvalidArgumentException("Please provide a valid Login");
            exit();
        }

        $customerQueryStr = "SELECT Password FROM Customers ". "WHERE Login = '$Login' ";
        $customerQueryPassword = $this->dbConnection->selectQuery($customerQueryStr);

        if (is_null($customerQueryPassword )){
            throw new MissingEntityException("Unable to find a customer with that Login.");
        }

        else if (strcmp($input, $customerQueryPassword) != 0) {
            throw new NoAccessException("Incorrect password was provided.");
        }

        else {
            $correctPw = TRUE;
        }

        return $correctPW;
    }
// update Customer information
// only information to update is FirstName
    public function update() {
        $FirstName   = $this->request->getParameter('customer-FirstName');
        $Login       = $this->request->getParameter('customer-Login');
        $Password    = $this->request->getParameter('customer-Password');
        $CreditCard  = $this->request->getParameter('customer-CreditCard');
        
        if (is_null($Login)) {
            header('Location: /');
            exit();
        }
// Password check
        if (checkPW($Login, $Password) || strlen($Password) == 0) {
            throw new InvalidArgumentException("Please provide a password for your account.");
        }
// assuming a valid credit card has 16 digits
        if (is_null($CreditCard) || count_digit($CreditCard) == 16) {
            throw new InvalidArgumentException("Please provide a valid credit card (16 digit) for your account.");
        }

        $validateQueryStr = "SELECT * FROM Users"."WHERE Login = '$Login' ";
        $validateResult = $this->dbConnection->selectQuery($validateQueryStr);

        if(!empty($validateResult)) {
            $updateQueryStr = "UPDATE Customers "."SET FirstName = '$FirstName'";

            $updated = $this->dbConnection->updateQuery($updateQueryStr);

            if (!$updated) {
                throw new SQLException("Failed to update customer's first name");
            }
         }
         else {
             throw new MissingEntityException("Unable to find User $username to update");
        }
    }

}