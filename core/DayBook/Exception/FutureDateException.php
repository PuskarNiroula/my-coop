<?php

namespace DayBook\Exception;

use Exception;

class FutureDateException extends Exception
{
    public function __construct(){
        parent::__construct("Future Date is not allowed");
    }

}
