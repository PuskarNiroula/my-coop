<?php

namespace App\Http\Controllers;

use App\Models\InterestRecord;
use App\Models\Month;
use App\Models\Year;
use Exception;
use Interest\Service\InterestService;
use Throwable;

class InterestController extends Controller
{
    public function __construct(
        private readonly InterestService $interestService,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        try {

            $records = InterestRecord::all();
            return view('saving.interest.index', compact( 'records'));
        } catch (Exception $e) {
            return redirect()->route('savings.index')->with('error', $e->getMessage());
        }
    }


    public function getStatement()
    {
        try {
            $reportDto = $this->interestService->getStatement();
            return view('saving.interest.statement', compact('reportDto'));

        } catch (Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function calculateInterest()
    {
        try {
            $interestRecord = $this->interestService->calculateAllInterest();
            return redirect()->route('savings.interest.get-transaction', $interestRecord);

        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());

        }

    }

    public function getTransaction(int $interestRecordId)
    {
        $records = $this->interestService->getDistributionRecords($interestRecordId);
        return view('saving.interest.distribution', compact('records'));
    }

    public function cancelInterestRecord(int $interestRecordId)
    {
        try {
            $this->interestService->cancelTransaction($interestRecordId);
            return response()->json([
                'message' => 'Interest Record has been cancelled Successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }


    }

}
