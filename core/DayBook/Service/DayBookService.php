<?php

namespace DayBook\Service;

use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use App\Models\DayBook;
use App\Models\SavingTransaction;
use App\ViewModel\DayBook\DayBookCloseResponseViewModel;
use App\ViewModel\DayBook\SavingViewModel;
use DayBook\Enum\DayBookStatusEnum;
use DayBook\Exception\DateBeforeLastDayBookDateException;
use DayBook\Exception\DayBookAlreadyOpenedException;
use DayBook\Exception\DayBookNotFoundException;
use DayBook\Exception\FutureDateException;
use Saving\Enum\SavingTransactionStatusEnum;
use Saving\Enum\SavingTransactionsTypeEnum;

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

    /**
     * @throws DayBookNotFoundException
     */
    public function getTransactionOfCurrentDay(int $id): DayBookCloseResponseViewModel
    {
        $dayBook = DayBook::query()->find($id);

        if (!$dayBook) {
            throw new DayBookNotFoundException($id);
        }

        $vm = new DayBookCloseResponseViewModel();

        $types = [
            SavingTransactionsTypeEnum::SAVINGS_DEPOSIT->value,
            SavingTransactionsTypeEnum::OPENING_BALANCE->value,
            SavingTransactionsTypeEnum::WITHDRAWAL->value,
        ];

        $savings = SavingTransaction::query()
            ->where('day_book_id', $id)
            ->whereIn('transaction_type', $types)
            ->where('status', SavingTransactionStatusEnum::ACTIVE->value)
            ->with('account.member')
            ->get();

        foreach ($savings as $saving) {
            $dto = new SavingViewModel();
            $dto->memberName = $saving->account->member->name;
            $dto->amount = $saving->amount;

            if ($saving->transaction_type === SavingTransactionsTypeEnum::WITHDRAWAL->value) {
                $vm->withdrawals[] = $dto;
            } else {
                $vm->savings[] = $dto;
            }
        }

        return $vm;
    }

}
