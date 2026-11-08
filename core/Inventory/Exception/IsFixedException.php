<?php

namespace Inventory\Exception;
use Exception;
class IsFixedException extends Exception
{
    public function __construct(){
        parent::__construct("This unit category is fixed it cannot be edited or deactivated", 1);
    }

}
