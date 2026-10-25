<?php

namespace AccountTypes\Exception;

use Exception;
use Throwable;
class AccountTypeNotFound extends Exception
{

    public function __construct(int $id = 0, int $code = 0, ?Throwable $previous = null)
    {
        $message = " account type with id  \"{id}\" not found.";
        parent::__construct($message, $code, $previous);
    }
}
