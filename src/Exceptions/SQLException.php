<?php declare(strict_type=1);

namespace GTheatre\Exceptions;

use \Exception;

class SQLException extends Exception{

    public function __construct($message, $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
    
}    
