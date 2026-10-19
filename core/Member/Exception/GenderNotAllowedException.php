<?php

namespace Member\Exception;

use Exception;

class GenderNotAllowedException extends Exception
{
    public function __construct(){
        parent::__construct("Gender not allowed. Gender must be either male, female or other");
    }


}
