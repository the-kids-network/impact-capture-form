<?php

namespace App\Exceptions;

use Exception;

class DuplicateException extends Exception {
    public $duplicateObjectId;

    // Redefine the exception so message isn't optional
    public function __construct($duplicateObjectId, $message, $code = 0) {
        parent::__construct($message, $code);

        $this->duplicateObjectId = $duplicateObjectId;
    }
}
