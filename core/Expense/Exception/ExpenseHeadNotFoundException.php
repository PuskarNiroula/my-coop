<?php

namespace Expense\Exception;
use Exception;

class ExpenseHeadNotFoundException extends Exception
{
    public function __construct($id=""){
        parent::__construct("Expense head with id: $id not found");
    }

}
