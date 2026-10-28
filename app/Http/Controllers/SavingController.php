<?php

namespace App\Http\Controllers;
use App\Exports\SavingAccountsTemplateExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
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

    /**
     * @throws \Throwable
     */
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




}
