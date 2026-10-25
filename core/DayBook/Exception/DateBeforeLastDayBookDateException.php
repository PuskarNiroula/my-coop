<?php

namespace DayBook\Exception;

use Exception;

class DateBeforeLastDayBookDateException extends Exception
{
    public function __construct(string $newDate, string $lastDayBookDate){
        parent::__construct("the last transaction date is $lastDayBookDate. You cannot create a transaction for $newDate");
    }

}
