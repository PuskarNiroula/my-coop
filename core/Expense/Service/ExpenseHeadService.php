<?php

namespace Expense\Service;

use App\Models\ExpenseHead;
use Expense\Enum\ExpenseHeadStatusEnum;
use Expense\Exception\DuplicateExpenseHeadException;
use Expense\Exception\ExpenseHeadNotFoundException;

class ExpenseHeadService
{

    /**
     * @throws DuplicateExpenseHeadException
     */
    public function createExpenseHead(string $name): void
    {
         if( ExpenseHead::query()->where('name', $name)->exists())
             throw new DuplicateExpenseHeadException($name);
         ExpenseHead::create([
             'name' => $name
         ]);
    }

    /**
     * @throws DuplicateExpenseHeadException
     */
    public function updateExpenseHead(string $name, int $id): void
    {
        if( ExpenseHead::query()->where('id','!=', $id)->where('name', $name)->exists())
            throw new DuplicateExpenseHeadException($name);
        ExpenseHead::query()->where('id', $id)->update([
            'name' => $name
        ]);
    }

    /**
     * @throws ExpenseHeadNotFoundException
     */
    public function activateExpenseHead(int $id): void{
        $expenseHead = ExpenseHead::query()->find($id);
        if(!$expenseHead) throw new ExpenseHeadNotFoundException($id);
        $expenseHead->status=ExpenseHeadStatusEnum::ACTIVE->value;
        $expenseHead->save();
    }
    /**
     * @throws ExpenseHeadNotFoundException
     */
    public function deactivateExpenseHead(int $id): void{
        $expenseHead = ExpenseHead::query()->find($id);
        if(!$expenseHead) throw new ExpenseHeadNotFoundException($id);
        $expenseHead->status=ExpenseHeadStatusEnum::INACTIVE->value;
        $expenseHead->save();
    }


}
