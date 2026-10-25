<?php

namespace DayBook\Helper;

use App\Models\DayBook;
use DayBook\Enum\DayBookStatusEnum;
use DayBook\Exception\NoOpenDayBookException;

class DayBookHelper
{
    /**
     * @throws NoOpenDayBookException
     */
    public static function getCurrentDaybook(): ?DayBook
    {
        $dayBook= DayBook::where('status', DayBookStatusEnum::OPEN->value)->first();
        if(!$dayBook)
            throw new NoOpenDayBookException();
        return $dayBook;
    }
}
