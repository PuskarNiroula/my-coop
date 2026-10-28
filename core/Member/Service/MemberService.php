<?php

namespace Member\Service;

use App\Models\AccountType;
use App\Models\MemberAccount;
use App\Models\SavingTransaction;
use DayBook\Exception\NoOpenDayBookException;
use DayBook\Helper\DayBookHelper;
use Member\Dto\ImportMemberDto;
use Member\Enum\GenderEnum;
use Member\Dto\MemberCreateDto;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Member\Exception\GenderNotAllowedException;
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
                return Member::create([
                    'name' => $dto->name,
                    'phone' => $dto->phone,
                    'father_name' => $dto->father_name,
                    'mother_name' => $dto->mother_name,
                    'address' => $dto->address,
                    'dob' => $dto->dob,
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

                $account_type_id = AccountType::where('name', $dto->account_type)->firstOrFail()->account_type_id;

               $account = MemberAccount::create([
                    'member_id' => $member->member_id,
                    "account_type_id"=>$account_type_id,
                    'opened_at' => now(),
                    "closed_at" => null,
                    'status'=>"active",
                   "balance"=>$dto->opening_balance,
                ]);

                $date = $dayBook->date_nepali;

                $parts = explode('-', $date);
                $month = (int) $parts[1];


                SavingTransaction::create([
                    'account_id' => $account->member_account_id,
                    'transaction_type'=>SavingTransactionsTypeEnum::INITIAL_DEPOSIT->value,
                    'amount'=>$dto->opening_balance,
                    'transaction_date'=>$dayBook->date_english,
                    'transaction_date_nepali'=>$dayBook->date_nepali,
                    'status'=>"active",
                    'created_by'=>auth()->id(),
                    "month_id"=>$month,
                    "amount_after_transaction"=>$dto->opening_balance,
                ]);

            });

        }
}
