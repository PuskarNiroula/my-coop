<?php

namespace Inventory\Exception;
use Exception;
class DuplicateUnitCategoryNameException extends Exception
{
    public function __construct(string $name){
        parent::__construct("Category with name:'{$name}' already exists", 1);
    }

}
