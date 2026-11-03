<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Models\ExpenseHead;
use App\Models\ExpenseRecord;
use App\Models\FiscalYear;
use App\Models\Month;
use App\Models\Year;
use Exception;
use Expense\Dto\ExpenseRecordCreateDto;
use Expense\Enum\ExpenseHeadStatusEnum;
use Expense\Service\ExpenseRecordService;
use Illuminate\Http\Request;
class ExpenseRecordController extends Controller
{

    public function __construct(
        private readonly ExpenseRecordService $expenseRecordService,
    ){

    }
    public function index(){
        $expenseRecords=ExpenseRecord::orderBy('expense_record_id')
            ->with('expenseHead')
            ->paginate(10);
       return view('expense.record.index',compact('expenseRecords'));

    }
    public function create(){
        $expenseHeads = ExpenseHead::where('status',ExpenseHeadStatusEnum::ACTIVE->value)->orderBy('name')->get();
        return view('expense.record.create',compact('expenseHeads'));

    }
    public function store(Request $request){
        try{
            $request->validate([
                'expense_head_id'=>'required|integer|exists:expense_heads,expense_head_id',
                'amount'=>'required|numeric|min:1',
                'description'=>'nullable|string',
            ]);
            $dto = new ExpenseRecordCreateDto();
            $dto->created_by = auth()->id();
            $dto->fromArray($request->toArray());
            $this->expenseRecordService->createExpenseRecord($dto);
            return redirect()->route('expense.records.index')->with('success', 'Expense Head Created Successfully');

        }catch (Exception $exception){
            return redirect()->route('expense.records.index')->with('error', $exception->getMessage());
        }
    }
    public function activate(int $id){
        try{
            $this->expenseRecordService->activateExpenseRecord($id);
            return redirect()->route('expense.records.index')->with('success', 'Expense Head Activated Successfully');
        }catch (Exception $exception){
            return redirect()->route('expense.records.index')->with('error', $exception->getMessage());
        }

    }
    public function deactivate(int $id){
        try{
            $this->expenseRecordService->deactivateExpenseRecord($id);
            return redirect()->route('expense.records.index')->with('success', 'Expense Head Activated Successfully');
        }catch (Exception $exception){
            return redirect()->route('expense.records.index')->with('error', $exception->getMessage());
        }

    }
    public function report(Request $request)
    {
        $months = Month::orderBy('month_id')->pluck('month_id','name');
        $years = Year::where('status','active')->pluck('year_id','name');
        $query = ExpenseRecord::with('expenseHead')->where('expense_records.status',ExpenseHeadStatusEnum::ACTIVE->value);


        if ($request->filled('month')) {
            $query->where('month_id', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year_id', $request->year);
        }

        $fiscalYearName = "";

        if ($request->filled('fiscal_year')) {
            $fiscalYearName = FiscalYear::where('fiscal_year_id', $request->fiscal_year)->first()->name??"N/A";
            $query->where('fiscal_year_id', $request->fiscal_year);
        }

        $expenseRecords = $query->latest()->get();

        $fiscalYears = FiscalYear::orderBy('fiscal_year_id')->pluck('name','fiscal_year_id');

        return view('expense.report.index', compact(
            'expenseRecords',
            'fiscalYears',
            'months',
            'years',
            'fiscalYearName'
        ));
    }

}
