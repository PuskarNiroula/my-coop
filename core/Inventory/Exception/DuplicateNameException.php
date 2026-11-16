<?php

namespace Inventory\Exception;
use Exception;
class DuplicateNameException extends Exception
{
    public function __construct(string $message){
        parent::__construct($message);
    }

}
