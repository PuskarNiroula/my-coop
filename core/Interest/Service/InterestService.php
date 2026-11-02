<?php

namespace Interest\Service;

use App\Models\InterestDistribution;
use App\Models\InterestRecord;
use App\Models\MemberAccount;
use App\Models\Month;
use App\Models\SavingMonthlyStatement;
use App\Models\SavingTransaction;
use App\Models\Year;
use App\ViewModel\InterestDistributionArrayViewModel;
use App\ViewModel\InterestDistributionViewModel;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Interest\Dto\MonthlyStatementDto;
use Interest\Dto\SemiAnnualReportDto;
use Interest\Dto\SemiAnnualStatementCreateDto;
use Interest\Enum\InterestRecordStatusEnum;
use Interest\Enum\SavingStatementStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;
use Exception;
use Throwable;

class InterestService
{
    /**
     * @throws Exception
     * @throws Throwable
     */
    public function getStatement(): SemiAnnualReportDto
    {
        $this->verifyDate();

        $dto = $this->getSemiAnnualStatementCreateDto();

        $existingStatement = SavingMonthlyStatement::query()
            ->where('year_id', $dto->endYearId)
            ->where('month_id', $dto->endMonthId)
            ->where('status', SavingStatementStatusEnum::ACTIVE->value)
            ->exists();

        if ($existingStatement) {
            return $this->getStatementForView($dto);
        }

        $rows = $this->buildSemiAnnualStatementRows($dto);

        return $this->buildReportDto($rows);
    }

    /**
     * @throws Exception
     */


    /**
     * @throws Exception
     */
    public function getYearIdAndMonthIdForInterestCalculation(): array
    {
        $lastDistribution = InterestRecord::query()
            ->where('status',InterestRecordStatusEnum::ACTIVE->value)
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

            abort_if(!$openingBalance, 500);

            $lastYearId = $openingBalance->year_id;
            $lastMonthId = $openingBalance->month_id - 1;
        }

        $lastYear = Year::findOrFail($lastYearId);

        $lastYearId = $lastYear->year_id;

        if ($lastMonthId == 9) {
            $lYear = Year::query()
                ->where('sequence', '>', $lastYear->sequence)
                ->orderBy('sequence')
                ->first();

            if (!$lYear) {
                throw new Exception(
                    "Please make sure to create a year that belongs to this calculation"
                );
            }

            $lastYear = $lYear;
        }

        $monthId = (($lastMonthId + 6) % 12);

        return [
            'startYearId' => $lastYearId,
            'startMonthId' => $lastMonthId == 12 ? 1 : $lastMonthId + 1,
            'endYearId' => $lastYear->year_id,
            'endMonthId' => $monthId
        ];
    }



    /**
     * Save the statement after user confirmation.
     *
     * @throws Throwable
     */
    public function saveSemiAnnualStatement(): void
    {
        $this->verifyDate();

        $dto = $this->getSemiAnnualStatementCreateDto();

        DB::transaction(function () use ($dto) {
            $periods = $dto->getPeriods();

            if (count($periods) !== 6) {
                throw ValidationException::withMessages([
                    'period' => 'Statement must contain exactly six months.',
                ]);
            }

            $existingStatement = SavingMonthlyStatement::query()
                ->where('status', SavingStatementStatusEnum::ACTIVE->value)
                ->where(function ($query) use ($periods) {
                    foreach ($periods as $period) {
                        $query->orWhere(function ($subQuery) use ($period) {
                            $subQuery->where('year_id', $period['year_id'])
                                ->where('month_id', $period['month_id']);
                        });
                    }
                })
                ->exists();

            if ($existingStatement) {
                throw ValidationException::withMessages([
                    'period' => 'A statement has already been saved for this period.',
                ]);
            }

            $statements = $this->buildSemiAnnualStatementRows($dto);

            if (empty($statements)) {
                throw ValidationException::withMessages([
                    'period' => 'No statement records found for the selected period.',
                ]);
            }

            SavingMonthlyStatement::upsert(
                $statements,
                ['member_account_id', 'year_id', 'month_id'],
                [
                    'fiscal_year_id',
                    'opening_balance',
                    'total_deposit',
                    'total_withdraw',
                    'closing_balance',
                    'status',
                    'updated_at',
                ]
            );
        });
    }

    /**
     * @throws Throwable
     */
    public function calculateAllInterest(): int
    {
        $this->saveSemiAnnualStatement();
        $dto = $this->getSemiAnnualStatementCreateDto();

        return DB::transaction(function () use ($dto) {
            $periods = $dto->getPeriods();

            if (count($periods) !== 6) {
                throw ValidationException::withMessages([
                    'period' => 'Interest must be calculated for exactly six months.',
                ]);
            }

            $startPeriod = ($dto->startYearId * 12) + $dto->startMonthId;
            $endPeriod = ($dto->endYearId * 12) + $dto->endMonthId;

            $overlappingRecord = InterestRecord::query()
                ->where('status', 'active')
                ->whereRaw(
                    '((start_year_id * 12 + start_month_id) <= ?)',
                    [$endPeriod]
                )
                ->whereRaw(
                    '((end_year_id * 12 + end_month_id) >= ?)',
                    [$startPeriod]
                )
                ->exists();

            if ($overlappingRecord) {
                throw ValidationException::withMessages([
                    'period' => 'Interest has already been distributed for an overlapping period.',
                ]);
            }

            $statements = SavingMonthlyStatement::query()
                ->with(['memberAccount.accountType'])
                ->where('status', SavingStatementStatusEnum::ACTIVE->value)
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
                ->get()
                ->groupBy('member_account_id');

            if ($statements->isEmpty()) {
                throw ValidationException::withMessages([
                    'period' => 'No monthly statements found for the selected period.',
                ]);
            }

            foreach ($statements as $memberAccountId => $monthlyStatements) {
                if ($monthlyStatements->count() !== 6) {
                    throw ValidationException::withMessages([
                        'period' => "Account {$memberAccountId} does not have all six monthly statements.",
                    ]);
                }
            }

            $interestRecord = InterestRecord::create([
                'fiscal_year_id' => getCurrentFiscalYear()->fiscal_year_id,
                'start_year_id' => $dto->startYearId,
                'start_month_id' => $dto->startMonthId,
                'end_year_id' => $dto->endYearId,
                'end_month_id' => $dto->endMonthId,
                'total_interest' => 0,
                'status' => 'active',
            ]);

            SavingMonthlyStatement::query()
                ->where('status', SavingStatementStatusEnum::ACTIVE->value)
                ->where(function ($query) use ($periods) {
                    foreach ($periods as $period) {
                        $query->orWhere(function ($subQuery) use ($period) {
                            $subQuery->where('year_id', $period['year_id'])
                                ->where('month_id', $period['month_id']);
                        });
                    }
                })
                ->update([
                    'interest_record_id' => $interestRecord->interest_record_id,
                ]);

            $totalInterest = 0;

            $nextMonthId = $dto->endMonthId == 12
                ? 1
                : $dto->endMonthId + 1;

            $nextYearId = $dto->endMonthId == 12
                ? $dto->endYearId + 1
                : $dto->endYearId;

            foreach ($statements as $memberAccountId => $monthlyStatements) {
                $memberAccount = $monthlyStatements->first()->memberAccount;

                $interestRate = (float) $memberAccount->accountType->interest_rate;

                $accountInterest = 0;

                foreach ($monthlyStatements as $statement) {
                    $monthlyClosingBalance = (float) $statement->closing_balance;

                    $monthlyInterest = $monthlyClosingBalance
                        * ($interestRate / 100)
                        / 12;

                    $accountInterest += round($monthlyInterest, 2);

                }

                $accountInterest = round($accountInterest, 2);

                $lastStatement = $monthlyStatements->last();

                $previousBalance = (float) $lastStatement->closing_balance;

                $newBalance = round($previousBalance + $accountInterest, 2);

                InterestDistribution::create([
                    'interest_record_id' => $interestRecord->interest_record_id,
                    'account_id' => $memberAccountId,
                    'opening_balance' => $previousBalance,
                    'interest_rate' => $interestRate,
                    'interest_amount' => $accountInterest,
                    'closing_balance' => $newBalance,
                ]);

                if ($accountInterest > 0) {
                    SavingTransaction::create([
                        'account_id' => $memberAccountId,
                        'year_id' => $nextYearId,
                        'month_id' => $nextMonthId,
                        'amount' => $accountInterest,
                        'amount_after_transaction' => $newBalance,
                        'transaction_type' => SavingTransactionsTypeEnum::INTEREST_PAYMENT->value,
                        'transaction_date' => getCurrentDayBook()->date_english,
                        'transaction_date_nepali' => getCurrentDayBook()->date_nepali,
                        'fiscal_year_id' => getCurrentFiscalYear()->fiscal_year_id,
                        'status' => 'active',
                        'interest_record_id' => $interestRecord->interest_record_id,
                    ]);
                }

                $memberAccount->update([
                    'balance' => $newBalance,
                ]);

                $totalInterest += $accountInterest;
            }

            $interestRecord->update([
                'total_interest' => round($totalInterest, 2),
            ]);

            return (int) $interestRecord->interest_record_id;
        });
    }

    public function getDistributionRecords(int $recordId): InterestDistributionArrayViewModel
    {
        $data = InterestDistribution::query()
            ->where('interest_record_id', $recordId)
            ->where('status', 'active')
            ->with('memberAccount.member')
            ->get();

        $vm = new InterestDistributionArrayViewModel();

        foreach ($data as $record) {
            $dto = new InterestDistributionViewModel();

            $dto->member = $record->memberAccount->member->name;
            $dto->openingBalance = $record->opening_balance;
            $dto->closingBalance = $record->closing_balance;
            $dto->interest = $record->interest_amount;
            $dto->rate = $record->interest_rate;

            $vm->distributionRecords[] = $dto;
        }

        return $vm;
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
        $monthInDayBook = (int) $parts[1];

        $actualMonth = Month::findOrFail($monthId);

        $exceptionMonthId = $monthId == 12 ? 1 : $monthId + 1;

        $exceptionMonth = Month::findOrFail($exceptionMonthId)->name;

        $actualYear = Year::findOrFail($yearId)->name;

        if ((string) $actualYear !== (string) $yearInDayBook) {
            throw new Exception("Please make sure year is $actualYear");
        }

        if (
            (int) $actualMonth->month_id === $monthInDayBook ||
            $exceptionMonthId === $monthInDayBook
        ) {
            return;
        }

        throw new Exception("Please make sure month is $actualMonth->name or $exceptionMonth");
    }

    private function getStatementForView(
        SemiAnnualStatementCreateDto $dto
    ): SemiAnnualReportDto {
        $periods = $dto->getPeriods();

        $statements = SavingMonthlyStatement::query()
            ->with([
                'memberAccount.member',
                'month',
                'year',
                'fiscalYear',
            ])
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

        return $this->buildReportDtoFromModels($statements);
    }


    /**
     * @throws Exception
     */
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

    /**
     * Generate monthly statement rows without saving them.
     *
     * @throws Throwable
     */
    private function buildSemiAnnualStatementRows(
        SemiAnnualStatementCreateDto $dto
    ): array {
        $periods = $dto->getPeriods();

        if (count($periods) !== 6) {
            throw ValidationException::withMessages([
                'period' => 'Statement must contain exactly six months.',
            ]);
        }

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
            return [];
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
            ->keyBy(fn ($row) => "{$row->account_id}_{$row->year_id}_{$row->month_id}");

        $previousPeriod = $dto->getPreviousPeriod();

        $previousStatements = SavingMonthlyStatement::query()
            ->whereIn('member_account_id', $accountIds)
            ->where('year_id', $previousPeriod['year_id'])
            ->where('month_id', $previousPeriod['month_id'])
            ->where('status', SavingStatementStatusEnum::ACTIVE->value)
            ->get()
            ->keyBy('member_account_id');

        $statements = [];

        $fiscalYearId = getCurrentFiscalYear()->fiscal_year_id;

        foreach ($accountIds as $accountId) {
            $previousClosingBalance = $previousStatements
                ->get($accountId)?->closing_balance;

            foreach ($periods as $index => $period) {
                $key = "{$accountId}_{$period['year_id']}_{$period['month_id']}";

                $transaction = $transactions->get($key);

                $deposits = (float) ($transaction?->deposits ?? 0);
                $withdrawals = (float) ($transaction?->withdrawals ?? 0);

                $openingBalance = $index === 0
                    ? ($previousClosingBalance ?? (float) ($transaction?->opening_amount ?? 0))
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

        return $statements;
    }

    /**
     * Convert calculated rows into the report DTO.
     */
    private function buildReportDto(array $rows): SemiAnnualReportDto
    {
        $statements = SavingMonthlyStatement::hydrate($rows);

        $statements->load([
            'memberAccount.member',
            'month',
            'year',
            'fiscalYear',
        ]);

        return $this->buildReportDtoFromModels($statements);
    }

    private function buildReportDtoFromModels($statements): SemiAnnualReportDto
    {
        $reportDto = new SemiAnnualReportDto();

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


    /**
     * @throws Throwable
     */
    public function cancelTransaction(int $id): void
    {
        $record = InterestRecord::find($id);

        if (!$record) {
            throw new RecordNotFoundException();
        }
        if($record->status == InterestRecordStatusEnum::INACTIVE->value)
            return;

        DB::beginTransaction();

        try {
            $record->status = InterestRecordStatusEnum::INACTIVE->value;
            $record->save();

            InterestDistribution::query()
                ->where('interest_record_id', $record->getId())
                ->update([
                    'status' => InterestRecordStatusEnum::INACTIVE->value,
                ]);

            SavingMonthlyStatement::query()
                ->where('interest_record_id', $record->getId())
                ->update([
                    'status' => InterestRecordStatusEnum::INACTIVE->value,
                ]);

            $transactions = SavingTransaction::query()
                ->where('interest_record_id', $record->getId())
                ->where('status', 'active')
                ->get();

            foreach ($transactions as $transaction) {
                $memberAccount = MemberAccount::query()
                    ->where('member_account_id', $transaction->account_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $memberAccount->decrement('balance', $transaction->amount);

                $transaction->update([
                    'status' => InterestRecordStatusEnum::INACTIVE->value,
                ]);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
