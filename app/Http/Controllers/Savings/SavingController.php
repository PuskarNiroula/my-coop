<?php

namespace App\Http\Controllers\Savings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;


class SavingController extends Controller
{

    public function index():View{
        return view("saving.index");
    }

    public function savingAccounts():View{
        return view("saving.accounts");
    }




}
