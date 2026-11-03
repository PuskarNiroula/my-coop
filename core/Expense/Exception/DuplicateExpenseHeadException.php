<?php

namespace Expense\Exception;
use Exception;

class DuplicateExpenseHeadException extends Exception
{
    public function __construct($name=""){
        parent::__construct("Expense head with name: $name already exists");
    }

}
