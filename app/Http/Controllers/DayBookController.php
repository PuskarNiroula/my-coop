<?php

namespace App\Http\Controllers;

use App\Models\DayBook;
use DayBook\Exception\DateBeforeLastDayBookDateException;
use DayBook\Exception\DayBookAlreadyOpenedException;
use DayBook\Exception\FutureDateException;
use DayBook\Service\DayBookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Matrix\Exception;
use mysqli_sql_exception;

class DayBookController extends Controller
{

    public function __construct(
        private readonly  DayBookService $dayBookService
    ){}

    public function index(): View
    {
        return view("daybook.index");

    }

    public function create(): View
    {
        $lastTransactionDateNepali = DayBook::orderByDesc('date_english')
        ->value('date_nepali');
        return view("daybook.create_day_book",compact('lastTransactionDateNepali'));
    }

    public function store(Request $request):RedirectResponse{
        $request->validate([
            'dateNepali'=>"required|string"
        ]);

        try{
            $this->dayBookService->createDayBook($request->dateNepali);
            return redirect()->route('daybook.create')->with('success','Day Book created successfully');
        }catch (DateBeforeLastDayBookDateException|Exception|DayBookAlreadyOpenedException|FutureDateException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
