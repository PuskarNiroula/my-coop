<?php

namespace Common\Exceptions;
use Exception;

class CurrentFiscalYearNotFoundException extends Exception
{
    public function __construct(){
        parent::__construct("Current fiscal year is not found. Please mark any fiscal year as current fiscal Year.");

    }

}
