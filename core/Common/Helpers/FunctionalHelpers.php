<?php

use App\Models\DayBook;
use App\Models\FiscalYear;
use App\Models\Month;
use App\Models\Year;
use Common\Exceptions\CurrentFiscalYearNotFoundException;
use Common\Exceptions\CurrentYearNotFoundException;
use DayBook\Enum\DayBookStatusEnum;

if (!function_exists('getCurrentMonth')) {
    function getCurrentMonth(): ?Month
    {
        return Month::where('is_current', true)->first();
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
    function getCurrentDayBook(){
        $dayBook = DayBook::where('status', DayBookStatusEnum::OPEN->value)->first();
        if(!$dayBook) throw new Exception("No opened Day Book");
        return $dayBook;
    }


}
