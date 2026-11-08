<?php

namespace Inventory\Exception;
use Exception;
class UnitCategoryDoesNotExistException extends Exception
{
    public function __construct($id){
        parent::__construct("Category with id:'{$id}' does not exits", 1);
    }

}
