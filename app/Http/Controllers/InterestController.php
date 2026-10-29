<?php

namespace App\Http\Controllers;

use App\Models\InterestDistribution;
use App\Models\Month;
use App\Models\SavingTransaction;
use App\Models\Year;
use Illuminate\Http\Request;
use Saving\Enum\SavingTransactionsTypeEnum;

class InterestController extends Controller
{
    public function index()
    {
        $lastDistribution = InterestDistribution::query()
            ->latest('created_at')
            ->first();

        if ($lastDistribution) {
            $lastYearId = $lastDistribution->end_year_id;
            $lastMonthId = $lastDistribution->end_month_id;
        } else {
            $openingBalance = SavingTransaction::query()
                ->where(
                    'transaction_type',
                    SavingTransactionsTypeEnum::OPENING_BALANCE->value
                )
                ->first();

            abort_if(!$openingBalance, 404);

            $lastYearId = $openingBalance->year_id;
            $lastMonthId = $openingBalance->month_id;
        }

        $lastYear = Year::findOrFail($lastYearId);

        if ($lastMonthId == 9) {
            $lastYear = Year::query()
                ->where('sequence', '>', $lastYear->sequence)
                ->orderBy('sequence')
                ->firstOrFail();
        }

        $monthId = (($lastMonthId + 5) % 12);

        $month = Month::findOrFail($monthId)->name;
        $yearName = $lastYear->name;

        return view('saving.interest.index', compact('yearName', 'month'));
    }

    public function calculateInterest(){
        $dayBook = getCurrentDayBook();
        //we will need this for date validation to check if the calculation is done in currect time or not


    }

}
