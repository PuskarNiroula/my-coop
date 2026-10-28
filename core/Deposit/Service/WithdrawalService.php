<?php

namespace Deposit\Service;

use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\Month;
use App\Models\SavingTransaction;
use Deposit\Dto\WithdrawalCreateDto;
use Exception;
use Illuminate\Support\Facades\DB;
use Saving\Enum\SavingTransactionStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;
use Saving\Exception\MemberNotFoundException;
use Throwable;

class WithdrawalService
{
    /**
     * @throws MemberNotFoundException
     * @throws Throwable
     */
    public function createWithdrawal(WithdrawalCreateDto $dto): void
    {
        if ($dto->amount <= 0) {
            throw new Exception('Withdrawal amount must be greater than zero.');
        }

        $this->validateMember($dto->memberId);
        $this->validateMonth($dto->monthId);

        $year = getCurrentYear();
        $fiscalYear = getCurrentFiscalYear();

        $this->validateDepositDate($dto->monthId, $year);

        DB::transaction(function () use ($dto, $year, $fiscalYear): void {
            $memberAccount = MemberAccount::query()
                ->where('member_id', $dto->memberId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($memberAccount->balance < $dto->amount) {
                throw new Exception(
                    'Cannot make withdrawal without enough balance.'
                );
            }

            $memberAccount->balance -= $dto->amount;
            $memberAccount->save();

            SavingTransaction::create([
                'account_id' => $memberAccount->member_account_id,
                'month_id' => $dto->monthId,
                'amount' => $dto->amount,
                'description' => $dto->description,
                'year_id' => $year->year_id,
                'transaction_type' => SavingTransactionsTypeEnum::WITHDRAWAL->value,
                'transaction_date' => getCurrentDayBook()->date_english,
                'transaction_date_nepali' => getCurrentDayBook()->date_nepali,
                'fiscal_year_id' => $fiscalYear->fiscal_year_id,
                'amount_after_transaction' => $memberAccount->balance,
                'status' => SavingTransactionStatusEnum::ACTIVE->value,
            ]);
        });
    }

    /**
     * @throws MemberNotFoundException
     */
    private function validateMember(int $memberId): void
    {
        if (!Member::query()->where('member_id', $memberId)->exists()) {
            throw new MemberNotFoundException($memberId);
        }
    }

    /**
     * @throws Exception
     */
    private function validateMonth(int $monthId): void
    {
        if (!Month::query()->where('month_id', $monthId)->exists()) {
            throw new Exception('Please select a valid month for deposit.');
        }
    }

    /**
     * @throws Exception
     */
    private function validateDepositDate(int $monthId, mixed $year): void
    {
        $lastOpeningTransaction = SavingTransaction::query()
            ->with('year')
            ->where(
                'transaction_type',
                SavingTransactionsTypeEnum::OPENING_BALANCE->value
            )
            ->orderByDesc('transaction_date')
            ->first();

        if (!$lastOpeningTransaction) {
            return;
        }

        $openingYear = $lastOpeningTransaction->year;

        if ($openingYear->sequence > $year->sequence) {
            throw new Exception(
                'Cannot process transactions for a past year.'
            );
        }

        if (
            $openingYear->sequence === $year->sequence
            && $lastOpeningTransaction->month_id > $monthId
        ) {
            throw new Exception(
                'Cannot deposit for a past month after interest calculation.'
            );
        }
    }

}
