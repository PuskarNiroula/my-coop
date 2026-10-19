<?php

namespace App\Http\Controllers;


use App\Exports\SavingAccountsTemplateExport;
use App\Models\Member;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SavingController extends Controller
{

    public function index():View{
        return view("saving.index");
    }

    public function savingAccounts():View{
        return view("saving.accounts");
    }

    public function create():View{
        return view("saving.create_saving");
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
    public function withdraw(): View{
        //we will need to fetch the members with their regular savings
        $members =[];
        return view("saving.withdraw",compact('members'));
    }


}
