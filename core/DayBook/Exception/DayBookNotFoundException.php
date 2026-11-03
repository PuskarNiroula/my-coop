<?php

namespace DayBook\Exception;

use Exception;

class DayBookNotFoundException extends Exception
{
    public function __construct($id){
        parent::__construct("DayBook with id ".$id." not found");
    }

}
