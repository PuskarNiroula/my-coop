<?php

namespace AccountTypes\Exception;

use Exception;
use Throwable;

class DuplicateAccountTypeException extends Exception
{
    public function __construct(string $name = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = " account type with name  \"{$name}\" already exists.";
        parent::__construct($message, $code, $previous);
    }

}
