<?php

namespace Saving\Exception;
use Exception;

class NotEnoughBalanceException extends Exception
{
    public function __construct(string $method){
        parent::__construct("Not enough balance in $method ");
    }

}
