<?php

namespace Inventory\Exception;
use Exception;
class DuplicateUnitNameException extends Exception
{
    public function __construct(string $name){
        parent::__construct("Unit with name:'{$name}' already exists", 1);
    }

}
