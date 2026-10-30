<?php

namespace Interest\Enum;

enum SavingStatementStatusEnum:string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case CONFIRMED = 'confirmed';

}
