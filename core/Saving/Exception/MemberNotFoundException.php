<?php

namespace Saving\Exception;

use Exception;
class MemberNotFoundException extends Exception
{
    public function __construct(int $member_id){
        parent::__construct("Member with id $member_id not found");
    }


}
