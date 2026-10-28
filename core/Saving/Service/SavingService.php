<?php

namespace Saving\Service;

use App\Models\Member;
use App\Models\RegularSavingAccount;
use App\Models\SavingTransaction;
use DayBook\Helper\DayBookHelper;
use Illuminate\Support\Facades\DB;
use Exception;
use Saving\Dto\SavingAccountCreateDto;
use Saving\Exception\MemberNotFoundException;
use Throwable;

class SavingService
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function createSavingAccount(SavingAccountCreateDto $dto):void{

        if(!Member::find($dto->memberId)->exists())
            throw new MemberNotFoundException($dto->memberId);


        DB::transaction(function () use ($dto){
            $dayBook = DayBookHelper::getCurrentDaybook();


       $savingAccount= RegularSavingAccount::create([
            'member_id'=>$dto->memberId,
            'amount'=>$dto->amount,
            'status'=>"active",
        ]);

       SavingTransaction::create([
           'regular_saving_id'=>$savingAccount->regular_saving_id,
           'transaction_type'=>$dto->transactionType,
           'amount'=>$dto->amount,
           'balance_before'=>0,
           'balance_after'=>$dto->amount,
           "transaction_date"=>$dayBook->date_english,
           "transaction_date_nepali"=>$dayBook->date_nepali,
           'day_book_id'=>$dayBook->day_book_id,
           'description'=>"Initial Deposit Or first Migration to software",
       ]);

        });

    }



}
