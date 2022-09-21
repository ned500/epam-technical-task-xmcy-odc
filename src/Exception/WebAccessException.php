<?php

namespace App\Exception;

use Exception;
use Throwable;

class WebAccessException extends Exception
{
    public function __construct(?Throwable $originalException = null, string $message = 'Web access issue')
    {
        parent::__construct($message, 0, $originalException);
    }
}
