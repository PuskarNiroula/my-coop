<?php

namespace Saving\Exception;
use Exception;

class SavingAccountNotFoundException extends Exception
{
    public function __construct(int $id){
        parent::__construct("Saving account for member with id $id not found");
    }

}
