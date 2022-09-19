<?php

namespace App\Exception;

use Exception;
use Throwable;

class WebAccessException extends Exception
{
    public function __construct(?Throwable $originalException = null)
    {
        parent::__construct('Web access issue', 0, $originalException);
    }
}
