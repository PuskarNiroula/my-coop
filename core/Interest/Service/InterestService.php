<?php

namespace Interest\Service;

use App\Models\InterestDistribution;
use App\Models\Month;
use App\Models\SavingMonthlyStatement;
use App\Models\SavingTransaction;
use App\Models\Year;
use Illuminate\Support\Facades\DB;
use Interest\Dto\MonthlyStatementDto;
use Interest\Dto\SemiAnnualReportDto;
use Interest\Dto\SemiAnnualStatementCreateDto;
use Interest\Enum\SavingStatementStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;
use Exception;
use Throwable;

class InterestService
{

    /**
     * @throws Exception|Throwable
     */
    public function getStatement(): SemiAnnualReportDto
    {
        $this->verifyDate();
        $dto = $this->getSemiAnnualStatementCreateDto();


       if(SavingMonthlyStatement::where('year_id',$dto->endYearId)
           ->where('month_id',$dto->endMonthId)
           ->where('status','active')
           ->exists()
       ){
           return $this->getStatementForView($dto);
       }

        $this->generateSemiAnnualStatement($dto);
       return $this->getStatementForView($dto);
    }

    /**
     * @throws Exception
     */
    private function verifyDate(): void
    {
        $dayBook = getCurrentDayBook();
        $arr = $this->getYearIdAndMonthIdForInterestCalculation();
        $yearId = $arr['endYearId'];
        $monthId = $arr['endMonthId'];
        $nepaliDate = $dayBook->date_nepali;
        $parts = explode('-', $nepaliDate);
        $yearInDayBook = $parts[0];
        $monthInDayBook = (int)$parts[1];

        $actualMonth = Month::findOrFail($monthId);
        $exceptionMonthId = $monthId == 12 ? 1 : $monthId + 1;
        $exceptionMonth = Month::findOrFail($exceptionMonthId)->name;
        $actualYear = Year::findOrFail($yearId)->name;
        if ($actualYear !== $yearInDayBook)
            throw new Exception("please make sure year is $actualYear");
        if ($actualMonth->month_id == $monthInDayBook || $monthId + 1 == $monthInDayBook) {
            return;
        }
        throw new Exception("please make sure month is $actualMonth or $exceptionMonth");
    }

    public function getYearIdAndMonthIdForInterestCalculation()
    {
        $lastDistribution = InterestDistribution::query()
            ->latest('created_at')
            ->first();

        if ($lastDistribution) {
            $lastYearId = $lastDistribution->end_year_id;
            $lastMonthId = $lastDistribution->end_month_id;
        } else {
            $openingBalance = SavingTransaction::query()
                ->where(
                    'transaction_type',
                    SavingTransactionsTypeEnum::OPENING_BALANCE->value
                )
                ->first();

            abort_if(!$openingBalance, 404);

            $lastYearId = $openingBalance->year_id;
            $lastMonthId = $openingBalance->month_id - 1;
        }

        $lastYear = Year::findOrFail($lastYearId);
        $lastYearId = $lastYear->year_id;

        if ($lastMonthId == 9) {
            $lastYear = Year::query()
                ->where('sequence', '>', $lastYear->sequence)
                ->orderBy('sequence')
                ->firstOrFail();
        }

        $monthId = (($lastMonthId + 5) % 12);

        return [
            "startYearId" => $lastYearId,
            'startMonthId' => $lastMonthId == 12 ? 1 : $lastMonthId + 1,
            "endYearId" => $lastYear->year_id,
            "endMonthId" => $monthId
        ];

    }
//    private function generateSemiAnnualStatement(SemiAnnualStatementCreateDto $dto){
//        $account_id = SavingTransaction::query()->whereIn('year_id',[$dto->startYearId,$dto->endYearId])
//            ->whereIn('month_id',$dto->getMonthsArray())
//            ->where('status','active')
//            ->distinct('account_id')
//        ->pluck('account_id')
//        ->toArray();
//
//        $arr=[];
//        $fiscalYearId= getCurrentFiscalYear()->fiscal_year_id;
//
//        foreach($account_id as $index=>$accountId){
//            $statementDto = new MonthlyStatementDto();
//            //first month
//            $amount = SavingTransaction::query()
//
//                ->where('account_id',$accountId)
//                ->where('fiscal_year_id',$fiscalYearId)
//                ->where('month_id',$dto->startMonthId)
//                ->newQuery(function(){
//                });
//
//
//
//
//
//        }
//
//
//    }

    /**
     * @throws Throwable
     */
    private function generateSemiAnnualStatement(SemiAnnualStatementCreateDto $dto): void
    {
        $periods = $dto->getPeriods();

        DB::beginTransaction();
        try {
            $fiscalYearId = getCurrentFiscalYear()->fiscal_year_id;

            $accountIds = SavingTransaction::query()
                ->where('status', 'active')
                ->where(function ($query) use ($periods) {
                    foreach ($periods as $period) {
                        $query->orWhere(function ($subQuery) use ($period) {
                            $subQuery->where('year_id', $period['year_id'])
                                ->where('month_id', $period['month_id']);
                        });
                    }
                })
                ->distinct()
                ->pluck('account_id');

            if ($accountIds->isEmpty()) {
                return;
            }

            $transactions = SavingTransaction::query()
                ->selectRaw('
            account_id,
            year_id,
            month_id,
            SUM(CASE WHEN transaction_type = ? THEN amount ELSE 0 END) AS deposits,
            SUM(CASE WHEN transaction_type = ? THEN amount ELSE 0 END) AS withdrawals,
            SUM(CASE WHEN transaction_type = ? THEN amount ELSE 0 END) AS opening_amount
        ', [
                    SavingTransactionsTypeEnum::SAVINGS_DEPOSIT->value,
                    SavingTransactionsTypeEnum::WITHDRAWAL->value,
                    SavingTransactionsTypeEnum::OPENING_BALANCE->value,
                ])
                ->whereIn('account_id', $accountIds)
                ->where('status', 'active')
                ->where(function ($query) use ($periods) {
                    foreach ($periods as $period) {
                        $query->orWhere(function ($subQuery) use ($period) {
                            $subQuery->where('year_id', $period['year_id'])
                                ->where('month_id', $period['month_id']);
                        });
                    }
                })
                ->groupBy('account_id', 'year_id', 'month_id')
                ->get()
                ->keyBy(fn($row) => "{$row->account_id}_{$row->year_id}_{$row->month_id}");

            $previousPeriod = $dto->getPreviousPeriod();

            $previousStatements = SavingMonthlyStatement::query()
                ->whereIn('member_account_id', $accountIds)
                ->where('year_id', $previousPeriod['year_id'])
                ->where('month_id', $previousPeriod['month_id'])
                ->orderBy('year_id')
                ->orderBy('month_id')
                ->get()
                ->keyBy('account_id');

            $statements = [];

            foreach ($accountIds as $accountId) {
                $previousClosingBalance = $previousStatements
                    ->get($accountId)?->closing_balance;

                foreach ($periods as $index => $period) {
                    $key = "{$accountId}_{$period['year_id']}_{$period['month_id']}";
                    $transaction = $transactions->get($key);

                    $deposits = $transaction?->deposits ?? 0;
                    $withdrawals = $transaction?->withdrawals ?? 0;

                    $openingBalance = $index === 0
                        ? ($previousClosingBalance ?? ($transaction?->opening_amount ?? 0))
                        : $previousClosingBalance;

                    $closingBalance = $openingBalance + $deposits - $withdrawals;

                    $statements[] = [
                        'member_account_id' => $accountId,
                        'year_id' => $period['year_id'],
                        'month_id' => $period['month_id'],
                        'fiscal_year_id' => $fiscalYearId,
                        'opening_balance' => $openingBalance,
                        'total_deposit' => $deposits,
                        'total_withdraw' => $withdrawals,
                        'closing_balance' => $closingBalance,
                        'status' => SavingStatementStatusEnum::ACTIVE->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $previousClosingBalance = $closingBalance;
                }
            }

            SavingMonthlyStatement::upsert(
                $statements,
                ['account_id', 'year_id', 'month_id'],
                [
                    'opening_balance',
                    'total_deposit',
                    'total_withdraw',
                    'closing_balance',
                    'updated_at',
                ]
            );
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    private function getStatementForView(SemiAnnualStatementCreateDto $dto): SemiAnnualReportDto
    {
        $periods = $dto->getPeriods();

        $reportDto = new SemiAnnualReportDto();

        $statements = SavingMonthlyStatement::query()
            ->with(['memberAccount', 'month', 'year', 'fiscalYear'])
            ->where(function ($query) use ($periods) {
                foreach ($periods as $period) {
                    $query->orWhere(function ($subQuery) use ($period) {
                        $subQuery->where('year_id', $period['year_id'])
                            ->where('month_id', $period['month_id']);
                    });
                }
            })
            ->orderBy('year_id')
            ->orderBy('month_id')
            ->orderBy('member_account_id')
            ->get();

        foreach ($statements as $statement) {
            $monthlyDto = new MonthlyStatementDto();

            $monthlyDto->memberName = $statement->memberAccount->member->name;
            $monthlyDto->month = $statement->month->name;
            $monthlyDto->year = $statement->year->name;
            $monthlyDto->fiscalYear = $statement->fiscalYear->name;

            $monthlyDto->openingBalance = (float) $statement->opening_balance;
            $monthlyDto->closingBalance = (float) $statement->closing_balance;
            $monthlyDto->totalDeposits = (float) $statement->total_deposit;
            $monthlyDto->totalWithdrawals = (float) $statement->total_withdraw;

            $reportDto->statements[] = $monthlyDto;
        }

        return $reportDto;
    }

    private function getSemiAnnualStatementCreateDto(): SemiAnnualStatementCreateDto
    {
        $dates = $this->getYearIdAndMonthIdForInterestCalculation();
        $dto = new SemiAnnualStatementCreateDto();
        $dto->startMonthId = $dates['startMonthId'];
        $dto->endMonthId = $dates['endMonthId'];
        $dto->startYearId = $dates['startYearId'];
        $dto->endYearId = $dates['endYearId'];
        return $dto;
    }


}
