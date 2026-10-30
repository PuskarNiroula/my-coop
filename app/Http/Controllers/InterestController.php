<?php

namespace App\Http\Controllers;

use App\Models\Month;
use App\Models\Year;
use Exception;
use Interest\Service\InterestService;

class InterestController extends Controller
{
    public function __construct(
        private readonly InterestService $interestService,
    ){
    }
    public function index()
    {
        $arr = $this->interestService->getYearIdAndMonthIdForInterestCalculation();
        $yearId = $arr['endYearId'];
        $monthId = $arr['endMonthId'];


        $month = Month::findOrFail($monthId)->name;
        $yearName = Year::findOrFail($yearId)->name;

        return view('saving.interest.index', compact('yearName', 'month'));
    }

    /**
     * @throws Exception
     */
    public function getStatement(){
        try{
           $reportDto =$this->interestService->getStatement();
           return view('saving.interest.statement', compact('reportDto'));

        }catch (Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function calculateInterest(){

    }


}
