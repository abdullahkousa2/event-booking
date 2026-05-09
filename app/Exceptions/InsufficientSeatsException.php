<?php

namespace App\Exceptions;

use Exception;

class InsufficientSeatsException extends Exception
{
    public function __construct(string $message = 'Not enough seats available')
    {
        parent::__construct($message);
    }
}
