<?php

namespace Common\Exceptions;
use Exception;

class CurrentYearNotFoundException extends Exception
{
    public function __construct(){
        parent::__construct("Current year is not found. Please mark any year as current year.");

    }

}
