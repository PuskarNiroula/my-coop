<?php

namespace DayBook\Service;

use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use App\Models\DayBook;
use DayBook\Enum\DayBookStatusEnum;
use DayBook\Exception\DateBeforeLastDayBookDateException;
use DayBook\Exception\DayBookAlreadyOpenedException;
use DayBook\Exception\FutureDateException;

class DayBookService
{

    /**
     * @throws DateBeforeLastDayBookDateException
     * @throws FutureDateException
     * @throws DayBookAlreadyOpenedException
     */
    public function createDayBook(string $date):void{

      $engDate=LaravelNepaliDate::from($date)->toEnglishDate();

      if(DayBook::query()->where('status',DayBookStatusEnum::OPEN->value)->exists())
          throw new DayBookAlreadyOpenedException();

      $lastDayBookDate = DayBook::query()->orderByDesc('date_english')->first();

      if( $lastDayBookDate && $lastDayBookDate->date_english>=$engDate){
          throw new DateBeforeLastDayBookDateException($date,$lastDayBookDate->date_nepali);
      }
//      if($engDate > date('Y-m-d'))
//          throw new FutureDateException();

      DayBook::create([
          'date_english'=>$engDate,
          'date_nepali'=>$date,
          'status'=>DayBookStatusEnum::OPEN->value,
      ]);

    }

}
