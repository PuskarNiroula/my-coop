<?php

namespace DayBook\Exception;
use Exception;

class NoOpenDayBookException extends Exception
{
    public function __construct(){
        parent::__construct("Please open a daybook first");
    }

}
