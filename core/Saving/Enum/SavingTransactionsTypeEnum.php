<?php

namespace Saving\Enum;

enum SavingTransactionsTypeEnum:string
{
    case INITIAL_DEPOSIT = 'initial_deposit';
    case SAVINGS_DEPOSIT = 'savings_deposit';
    case WITHDRAWAL = 'withdrawal';
    case INTEREST_PAYMENT = 'interest_payment';
    case OTHER = 'other';


}
