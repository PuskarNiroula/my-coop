<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use Exception;
use Expense\Exception\ExpenseHeadNotFoundException;
use Expense\Service\ExpenseHeadService;
use Illuminate\Http\Request;
class ExpenseHeadController extends Controller
{

    public function __construct(
        private readonly ExpenseHeadService $expenseHeadService
    ){

    }
    public function index(){
        $expenseHeads=ExpenseHead::orderBy('name')->paginate(10);
       return view('expense.expense-head.index',compact('expenseHeads'));

    }
    public function create(){
        return view('expense.expense-head.create');

    }
    public function store(Request $request){
        try{
            $request->validate([
                'name'=>'required|string|max:255',
            ]);
            $this->expenseHeadService->createExpenseHead($request->name);
            return redirect()->route('expenses.index')->with('success', 'Expense Head Created Successfully');

        }catch (Exception $exception){
            return redirect()->route('expenses.index')->with('error', $exception->getMessage());
        }
    }
    public function edit(int $id){
        $expenseHead = ExpenseHead::find($id);
        try{
            if(!$expenseHead){
                throw new ExpenseHeadNotFoundException($id);
            }
            return view('expense.expense-head.edit', compact('expenseHead'));
        }catch (Exception $exception){
            return redirect()->route('expenses.index')->with('error', $exception->getMessage());
        }

    }
    public function update(int $id, Request $request){
        try{
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $this->expenseHeadService->updateExpenseHead($request->name,$id);
        return redirect()->route('expenses.index')->with('success', 'Expense Head Updated Successfully');

        }catch (Exception $exception){
            return redirect()->route('expenses.index')->with('error', $exception->getMessage());
        }

    }
    public function activate(int $id){
        try{
            $this->expenseHeadService->activateExpenseHead($id);
            return redirect()->route('expenses.index')->with('success', 'Expense Head Activated Successfully');
        }catch (Exception $exception){
            return redirect()->route('expenses.index')->with('error', $exception->getMessage());
        }

    }
    public function deactivate(int $id){
        try{
            $this->expenseHeadService->deactivateExpenseHead($id);
            return redirect()->route('expenses.index')->with('success', 'Expense Head Activated Successfully');
        }catch (Exception $exception){
            return redirect()->route('expenses.index')->with('error', $exception->getMessage());
        }

    }

}
