<?php declare(strict_types=1);

/**
* Note that _SESSION and a lot of the variables are predefined 
* Check php Documentation
**/

namespace GTheatre\Session;

class PHPSession implements SessionWrapper {

    public function getValue($key) {
        // TO DELETE LATER !!!
        echo 'GetValue of PHPSESSION';

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        else {
           // echo 'GetValue of PHPSESSION';
            return null;
        }
    }

    public function setValue($key, $value){
        $_SESSION[$key] = $value;
    }
}