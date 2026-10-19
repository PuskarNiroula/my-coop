<?php

namespace Authentication\Exception;
use Exception;

class InvalidEmailOrPasswordException extends Exception
{
    public function __construct(){
        parent::__construct("Invalid email or password");
    }

}
