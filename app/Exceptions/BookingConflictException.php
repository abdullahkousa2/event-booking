<?php

namespace App\Exceptions;

use Exception;

class BookingConflictException extends Exception
{
    public function __construct(string $message = 'You have already booked this event')
    {
        parent::__construct($message);
    }
}
