<?php

namespace Saving\Exception;
use Exception;

class PaymentMethodNotFoundException extends Exception
{
    public function __construct(){
        parent::__construct("Payment Method not found");
    }

}
