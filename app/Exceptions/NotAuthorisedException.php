<?php

namespace App\Exceptions;

use Exception;

class NotAuthorisedException extends Exception {
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0) {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($message, $code);
    }
}
