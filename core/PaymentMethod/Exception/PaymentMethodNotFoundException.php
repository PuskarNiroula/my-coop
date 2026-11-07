<?php

namespace PaymentMethod\Exception;
use Exception;

class PaymentMethodNotFoundException extends Exception
{
    public function __construct(int $id){

        parent::__construct("Payment Method with id: $id not found");

    }

}
