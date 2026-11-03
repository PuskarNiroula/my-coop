<?php

use App\Models\DayBook;
use App\Models\FiscalYear;
use App\Models\Month;
use App\Models\Year;
use Common\Exceptions\CurrentFiscalYearNotFoundException;
use Common\Exceptions\CurrentYearNotFoundException;
use DayBook\Enum\DayBookStatusEnum;

if (!function_exists('getCurrentMonth')) {
    /**
     * @throws Exception
     */
    function getCurrentMonth(): ?Month
    {
        $dayBook = getCurrentDayBook();
        $nepaliDate = $dayBook->date_nepali;
        $parts = explode('-', $nepaliDate);
        return Month::query()->where('month_id', $parts[1])->first();
    }

    /**
     * @throws CurrentYearNotFoundException
     */
    function getCurrentYear(): ?Year{
        $year = Year::where('is_current', true)->first();
        if(!$year) throw new CurrentYearNotFoundException();
        return $year;

    }
    /**
     * @throws CurrentFiscalYearNotFoundException
     */
    function getCurrentFiscalYear(): ?FiscalYear{
        $fiscalYear = FiscalYear::where('is_current', true)->first();
        if(!$fiscalYear) throw new CurrentFiscalYearNotFoundException();
        return $fiscalYear;
    }

    /**
     * @throws Exception
     */
    function getCurrentDayBook():DayBook{
        $dayBook = DayBook::where('status', DayBookStatusEnum::OPEN->value)->first();
        if(!$dayBook) throw new Exception("No opened Day Book");
        return $dayBook;
    }


    /**
     * @throws Exception
     */
    function validateMonth(int $monthId): void
    {
        if (!Month::query()->where('month_id', $monthId)->exists())
            throw new Exception('Please select a valid month for deposit.');
        $currentMonth = getCurrentMonth();
        if($monthId != $currentMonth->getId())
            throw new Exception("Current month is $currentMonth->name, please make sure you are on right date");
    }


}
