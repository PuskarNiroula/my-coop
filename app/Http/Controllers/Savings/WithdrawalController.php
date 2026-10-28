<?php

namespace App\Http\Controllers\Savings;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\Month;
use Deposit\Dto\WithdrawalCreateDto;
use Deposit\Service\WithdrawalService;
use Exception;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{

    public function __construct(
        private readonly WithdrawalService $withdrawalService
    ){}

    public function index(){
        try{
            $members = Member::query()
                ->join('member_accounts as ma', 'members.member_id', '=', 'ma.member_id')
                ->where('ma.status', 'active')
                ->select('members.*')
                ->distinct()
                ->get();
            $monthArray = Month::all()->sortBy('month_id')->pluck('name','month_id');

            $nepaliDate = getCurrentDayBook()->date_nepali;

            $month = (int) explode('-', $nepaliDate)[1];

            return view('saving.withdrawal', compact(['members', 'monthArray','month']));
        }catch (Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function getMemberAmount(int $memberId){
        return response()->json($this->getAmount($memberId));
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request){
        $request->validate([
            'month_id' => 'required|integer|exists:months,month_id',
            'member_id' => 'required|integer|exists:members,member_id',
            'amount' => 'required|numeric|min:1',
            'description'=>"string|nullable",
        ]);

        $allowedAmount = $this->getAmount($request->member_id);
        if($allowedAmount < $request->amount)
            return redirect()->back()->withErrors("Cannot withdraw more than $allowedAmount")->withInput();

        $dto = new WithdrawalCreateDto();
        $dto->memberId = $request->member_id;
        $dto->monthId = $request->month_id;
        $dto->amount = $request->amount;
        $dto->description = $request->description;

        try{
            $this->withdrawalService->createWithdrawal($dto);
            return redirect()->route('withdrawals.index')->with('success', 'Withdrawal created successfully');

        }catch (Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    private function getAmount(int $memberId){
        $member = MemberAccount::findOrFail($memberId);
        return $member->balance;

    }

}
