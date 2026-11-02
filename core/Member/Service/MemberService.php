<?php

namespace Member\Service;

use App\Models\AccountType;
use App\Models\MemberAccount;
use App\Models\SavingTransaction;
use DayBook\Exception\NoOpenDayBookException;
use DayBook\Helper\DayBookHelper;
use Exception;
use Member\Dto\ImportMemberDto;
use Member\Enum\GenderEnum;
use Member\Dto\MemberCreateDto;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Member\Exception\GenderNotAllowedException;
use Saving\Enum\SavingTransactionStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;
use Throwable;

class MemberService
{
    /**
     * @throws Throwable
     * @throws GenderNotAllowedException
     */
    public function create(MemberCreateDto $dto): Member
    {
        $storedFiles = [];

        if(!in_array($dto->gender, GenderEnum::values()))
            throw new GenderNotAllowedException();

        try {

          if(AccountType::find($dto->account_type)==null)
              throw new Exception("Unknown account type".$dto->account_type);

            $citizenshipFront = $dto->citizenship_front
                ? $dto->citizenship_front->store('members/citizenship', 'public')
                : null;

            if ($citizenshipFront) {
                $storedFiles[] = $citizenshipFront;
            }

            $citizenshipBack = $dto->citizenship_back
                ? $dto->citizenship_back->store('members/citizenship', 'public')
                : null;

            if ($citizenshipBack) {
                $storedFiles[] = $citizenshipBack;
            }

            $image = $dto->image
                ? $dto->image->store('members/photos', 'public')
                : null;

            if ($image) {
                $storedFiles[] = $image;
            }

            return DB::transaction(function () use (
                $dto,
                $citizenshipFront,
                $citizenshipBack,
                $image
            ) {
                $member= Member::create([
                    'name' => $dto->name,
                    'phone' => $dto->phone,
                    'father_name' => $dto->father_name,
                    'mother_name' => $dto->mother_name,
                    'address' => $dto->address,
                    'birth_date_nepali' => $dto->dob,
                    'birth_date_eng' => toEnglishDate($dto->dob),
                    'gender' => $dto->gender,
                    'citizenship_number' => $dto->citizenship_number,
                    'legal_aid_type' => $dto->legal_aid_type,
                    'legal_aid_name' => $dto->legal_aid_name,
                    'legal_aid_relation' => $dto->legal_aid_relation,
                    'legal_aid_citizenship_number' => $dto->legal_aid_citizenship_number,
                    'citizenship_front' => $citizenshipFront,
                    'citizenship_back' => $citizenshipBack,
                    'image' => $image,
                ]);

                $account= MemberAccount::create([
                     "member_id"=>$member->getId(),
                     "account_type_id"=>AccountType::findOrFail($dto->account_type)->getId(),
                     "balance"=>$dto->opening_balance,
                     'opened_at'=>now(),
                     'status'=>"active",
                 ]);
                $date = getCurrentDayBook();
                SavingTransaction::create([
                    "account_id"=>$account->getId(),
                    'transaction_type'=>SavingTransactionsTypeEnum::OPENING_BALANCE->value,
                    'amount'=>$dto->opening_balance,
                    'amount_after_transaction'=>$dto->opening_balance,
                    'transaction_date'=>$date->date_english,
                    'transaction_date_nepali'=>$date->date_nepali,
                    'status'=>SavingTransactionStatusEnum::ACTIVE->value,
                    'year_id'=>getCurrentYear()->getId(),
                    'fiscal_year_id'=>getCurrentFiscalYear()->getId(),
                    'month_id'=>$this->getCurrentNepaliMonth($date->date_nepali)
                ]);

                return $member;


            });
        } catch (Throwable $exception) {
            foreach ($storedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $exception;
        }
    }


    /**
     * @throws Throwable
     */
    public function createFromImport(ImportMemberDto $dto)
        {
            return DB::transaction(function () use ($dto) {
               $dayBook= DayBookHelper::getCurrentDaybook();


               if(!$dayBook) throw new NoOpenDayBookException();
                $member= Member::create([
                    'name' => $dto->name,
                    'gender' => $dto->gender,
                    'phone' => $dto->phone,
                    'father_name' => $dto->father_name,
                    'mother_name' => $dto->mother_name,
                    'address' => $dto->address,
                    'dob' => $dto->dob,
                    'citizenship_number' => $dto->citizenship_number,
                    'legal_aid_type' => $dto->legal_aid_type,
                    'legal_aid_name' => $dto->legal_aid_name,
                    'legal_aid_relation' => $dto->legal_aid_relation,
                    'legal_aid_citizenship_number' => $dto->legal_aid_citizenship_number,
                    'status'=>"active",
                ]);


               $account = MemberAccount::create([
                    'member_id' => $member->member_id,
                    "account_type_id"=>$dto->account_type,
                    'opened_at' => now(),
                    "closed_at" => null,
                    'status'=>"active",
                   "balance"=>$dto->opening_balance,
                ]);

                SavingTransaction::create([
                    'account_id' => $account->member_account_id,
                    'transaction_type'=>SavingTransactionsTypeEnum::OPENING_BALANCE->value,
                    'amount'=>$dto->opening_balance,
                    'transaction_date'=>$dayBook->date_english,
                    'transaction_date_nepali'=>$dayBook->date_nepali,
                    'status'=>"active",
                    'created_by'=>auth()->id(),
                    "month_id"=>$this->getCurrentNepaliMonth($dayBook->date_nepali),
                    "amount_after_transaction"=>$dto->opening_balance,
                    "year_id"=>getCurrentYear()->year_id,
                    'fiscal_year_id'=>getCurrentFiscalYear()->fiscal_year_id,
                ]);

            });

        }

        private function getCurrentNepaliMonth(string $date):int{
            $parts = explode('-', $date);
            return (int) $parts[1];
        }

}
