<?php

namespace App\Http\Controllers;


use App\Exports\SavingAccountsTemplateExport;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Saving\Dto\DepositCreateDto;
use Saving\Dto\SavingAccountCreateDto;
use Saving\Enum\SavingTransactionsTypeEnum;
use Saving\Service\SavingService;

class SavingController extends Controller
{

    public function __construct(
      private readonly SavingService $savingService
    ){}

    public function index():View{
        return view("saving.index");
    }

    public function savingAccounts():View{
        return view("saving.accounts");
    }

    public function create():View{
        return view("saving.create_saving");
    }
    public function storeSaving(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.member_id' => ['required', 'distinct', 'exists:members,member_id'],
            'rows.*.balance' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ]);

        try {
            foreach ($validated['rows'] as $row) {
                $dto = new SavingAccountCreateDto();
                $dto->amount = $row['balance'];
                $dto->memberId = $row['member_id'];
                $dto->transactionType = SavingTransactionsTypeEnum::INITIAL_DEPOSIT->value;

                $this->savingService->createSavingAccount($dto);
            }

            return redirect()
                ->route('savings.accounts')
                ->with('success', 'Savings accounts created successfully.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function import():View{
        return view("saving.import_saving");
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new SavingAccountsTemplateExport(),
            'saving_accounts_import.xlsx'
        );
    }
    public function deposit(): View
    {
        $members = Member::query()
            ->where('is_active', true)
            ->whereHas('savingAccounts')
            ->orderBy('name')
            ->get(['member_id', 'name', 'phone']);

        return view('saving.deposit', compact('members'));
    }
    public function storeDeposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,member_id',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        $dto = new DepositCreateDto();
        $dto->memberId = $validated['member_id'];
        $dto->amount = $validated['amount'];
        $dto->remarks= $validated['remarks'];
        $dto->transactionType=SavingTransactionsTypeEnum::SAVINGS_DEPOSIT->value;
        try{
            $this->savingService->submitDeposit($dto);
            return redirect()->route('savings.deposit.create')->with('success', 'Deposit submitted successfully.');

        }catch (Exception $e){
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function withdraw(): View{
        //we will need to fetch the members with their regular savings
        $members =Member::query()->where('is_active',true)->whereHas('savingAccount')->orderBy('name')->with('savingAccount')->get();
        return view("saving.withdraw",compact('members'));
    }


}
