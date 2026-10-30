<?php

namespace Interest\Dto;

class MonthlyStatementDto
{
    public string $memberName;
    public string $month;
    public string $year;
    public string $fiscalYear;
    public float $openingBalance;
    public float $closingBalance;
    public float $totalDeposits;
    public float $totalWithdrawals;


}
