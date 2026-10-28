<?php

namespace App\Http\Controllers\Savings;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\Month;
use App\Models\SavingTransaction;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Saving\Enum\SavingTransactionStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;
use Exception;
use Throwable;

class SavingReportsController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'member_id' => ['nullable', 'integer'],
            'fiscal_year_id' => ['nullable', 'integer'],
            'year_id' => ['nullable', 'integer'],
            'month_id' => ['nullable', 'integer'],
            'transaction_date_nepali' => ['nullable', 'string', 'max:30'],
        ]);

        $buildQuery = function (array $type) use ($filters) {
            $query = SavingTransaction::query()
                ->with(['account.member', 'fiscalYear', 'year', 'month'])
                ->whereIn('transaction_type', $type);

            if (!empty($filters['member_id'])) {
                $query->whereHas('account', function ($accountQuery) use ($filters) {
                    $accountQuery->where('member_id', $filters['member_id']);
                });
            }

            foreach (['fiscal_year_id', 'year_id', 'month_id'] as $column) {
                if (!empty($filters[$column])) {
                    $query->where($column, $filters[$column]);
                }
            }

            if (!empty($filters['transaction_date_nepali'])) {
                $query->where(
                    'transaction_date_nepali',
                    $filters['transaction_date_nepali']
                );
            }

            return $query->latest('transaction_date');
        };

        $savings = $buildQuery(
            [SavingTransactionsTypeEnum::SAVINGS_DEPOSIT->value,SavingTransactionsTypeEnum::OPENING_BALANCE->value],
        )->paginate(10, ['*'], 'savings_page')->withQueryString();

        $withdrawals = $buildQuery(
            [SavingTransactionsTypeEnum::WITHDRAWAL->value]
        )->paginate(10, ['*'], 'withdrawals_page')->withQueryString();

        $members = Member::query()->orderBy('name')->get();
        $fiscalYears = FiscalYear::query()->orderByDesc('fiscal_year_id')->get();
        $years = Year::query()->orderByDesc('year_id')->get();
        $months = Month::query()->orderBy('month_id')->get();

        return view('saving.reports.index', compact(
            'savings',
            'withdrawals',
            'members',
            'fiscalYears',
            'years',
            'months',
            'filters'
        ));
    }

    /**
     * @throws Throwable
     */
    public function cancel(int $transactionId)
    {

        $dayBookDate = getCurrentDayBook()->date_english->toDateString();
        DB::transaction(function () use ($transactionId, $dayBookDate) {
            $transaction = SavingTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transactionId);


            if($transaction->transaction_date!=$dayBookDate){
                throw new Exception("transaction can be cancelled after new day book is opened");
            }

            if ($transaction->status !== SavingTransactionStatusEnum::ACTIVE->value) {
                throw new Exception('This transaction is already cancelled or inactive.');
            }

            $account = MemberAccount::query()
                ->where('member_account_id', $transaction->account_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $transaction->transaction_type === SavingTransactionsTypeEnum::SAVINGS_DEPOSIT->value
            ) {
                if ($account->balance < $transaction->amount) {
                    throw new Exception(
                        'This deposit cannot be cancelled because the current balance is insufficient.'
                    );
                }

                $account->balance -= $transaction->amount;
            } elseif (
                $transaction->transaction_type === SavingTransactionsTypeEnum::WITHDRAWAL->value
            ) {
                $account->balance += $transaction->amount;
            }

            $account->save();

            $transaction->status = SavingTransactionStatusEnum::INACTIVE->value;
            $transaction->save();
        });

        return redirect()
            ->route('saving-reports.index', request()->query())
            ->with('success', 'Transaction cancelled successfully.');
    }
}
