<?php

namespace DayBook\Exception;

use Exception;

class DayBookAlreadyOpenedException extends Exception
{
    public function __construct(){
        parent::__construct("DayBook already opened please close it first to create a new one");
    }

}
