<?php

namespace AccountTypes\Service;

use AccountTypes\Dto\AccountTypeCreateDto;
use AccountTypes\Dto\AccountTypeUpdateDto;
use AccountTypes\Enum\AccountTypeStatusEnum;
use AccountTypes\Exception\AccountTypeNotFound;
use AccountTypes\Exception\DuplicateAccountTypeException;
use App\Models\AccountType;

class AccountTypeService
{
    /**
     * @throws DuplicateAccountTypeException
     */
    public function create(AccountTypeCreateDto $dto ):void{
      if( AccountType::where("name",$dto->name)->exists()) throw new DuplicateAccountTypeException($dto->name);

        AccountType::create([
            "name" => $dto->name,
            'interest_payout_frequency'=>$dto->interestCalculationFrequency,
            'interest_rate'=>$dto->interestRate,
            'status'=>AccountTypeStatusEnum::ACTIVE,
        ]);
    }

    /**
     * @throws AccountTypeNotFound
     */
    public function activate(int $id):void{
        $accountType = AccountType::find($id)->first();
        if(!$accountType) throw new AccountTypeNotFound($id);

        $accountType->activate();
    }

    /**
     * @throws AccountTypeNotFound
     */
    public function deactivate(int $id):void{
        $accountType = AccountType::find($id)->first();
        if(!$accountType) throw new AccountTypeNotFound($id);

        $accountType->deactivate();
    }

    /**
     * @throws AccountTypeNotFound
     * @throws DuplicateAccountTypeException
     */
    public function update(AccountTypeUpdateDto $dto):void{


       if(AccountType::where('account_type_id',"!=",$dto->accountTypeId)->where('name',$dto->name)->exists())
           throw new DuplicateAccountTypeException($dto->name);

        $accountType = AccountType::find($dto->accountTypeId);
        if(!$accountType) throw new AccountTypeNotFound($dto->accountTypeId);

        $accountType->name = $dto->name;
        $accountType->interest_payout_frequency = $dto->interestCalculationFrequency;
        $accountType->interest_rate = $dto->interestRate;
        $accountType->save();

    }


}
