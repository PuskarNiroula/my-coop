<?php

namespace Expense\Service;

use App\Models\ExpenseRecord;
use Common\Exceptions\CurrentFiscalYearNotFoundException;
use Common\Exceptions\CurrentYearNotFoundException;
use Expense\Dto\ExpenseRecordCreateDto;
use Expense\Enum\ExpenseHeadStatusEnum;

use Exception;

class ExpenseRecordService
{

    /**
     * @throws CurrentFiscalYearNotFoundException
     * @throws CurrentYearNotFoundException
     * @throws Exception
     */
    public function createExpenseRecord(ExpenseRecordCreateDto $dto):void{
        if($dto->amount <= 0) throw new Exception("Amount should be greater than 0");
        ExpenseRecord::create([
            'expense_head_id' => $dto->expense_head_id,
            'amount' => $dto->amount,
            'description' => $dto->description,
            'fiscal_year_id'=>getCurrentFiscalYear()->fiscal_year_id,
            'status' => ExpenseHeadStatusEnum::ACTIVE,
            'year_id'=>getCurrentYear()->getId(),
            'month_id'=>getCurrentMonth()->getId(),
            'day_book_id'=>getCurrentDayBook()->getId(),
            'created_by' => $dto->created_by,
        ]);
    }

    /**
     * @throws Exception
     */
    public function activateExpenseRecord(int $id):void{
        $expenseRecord = ExpenseRecord::find($id);
        if(!$expenseRecord) throw new Exception("Expense Record not found");

        $dayBook = getCurrentDayBook();
        if($dayBook->getId() != $expenseRecord->day_book_id)
            throw new Exception("Expense Record cannot be activated after new day book is opened");
        $expenseRecord->update([
            'status' => ExpenseHeadStatusEnum::ACTIVE->value,
        ]);

    }

    /**
     * @throws Exception
     */
    public function deactivateExpenseRecord(int $id):void{
        $expenseRecord = ExpenseRecord::find($id);
        if(!$expenseRecord) throw new Exception("Expense Record not found");

        $dayBook = getCurrentDayBook();
        if($dayBook->getId() != $expenseRecord->day_book_id)
            throw new Exception("Expense Record cannot be deactivated after new day book is opened");
        $expenseRecord->update([
            'status' => ExpenseHeadStatusEnum::INACTIVE->value,
        ]);

    }

}
