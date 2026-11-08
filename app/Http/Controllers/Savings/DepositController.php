<?php


namespace App\Http\Controllers\Savings;



use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Month;
use App\Models\PaymentMethod;
use Deposit\Dto\CreateDepositDto;
use Deposit\Service\DepositService;
use Exception;
use Illuminate\Http\Request;
use PaymentMethod\Enum\PaymentMethodStatusEnum;
use Throwable;

class DepositController extends Controller
{

    public function __construct(
        private readonly DepositService $depositService
    ){}

    /**
     * @throws Exception
     */
    public function index()
    {
       try{
           $paymentMethods=PaymentMethod::where('status',PaymentMethodStatusEnum::ACTIVE->name)->get();
           $members = Member::query()
               ->join('member_accounts as ma', 'members.member_id', '=', 'ma.member_id')
               ->where('ma.status', 'active')
               ->select('members.*')
               ->distinct()
               ->get();
           $monthArray = Month::all()->sortBy('month_id')->pluck('name','month_id');

           $nepaliDate = getCurrentDayBook()->date_nepali;

           $month = (int) explode('-', $nepaliDate)[1];

               return view('saving.deposit', compact(['members', 'monthArray','month','paymentMethods']));
       }catch (Exception $e){
           return redirect()->back()->withErrors(['error' => $e->getMessage()]);
       }
    }
    /**
     * @throws Throwable
     */
    public function makeDeposit(Request $request){

        $request->validate([
            'member_id'=>'required|numeric|exists:members,member_id',
            'month_id'=>'required|numeric|exists:months,month_id',
            'amount'=>'required|numeric|min:1',
            'description'=>'nullable|string',
            'payment_method_id'=>'required|numeric|exists:payment_methods,payment_method_id',
        ]);


        $dto = new CreateDepositDto();
        $dto->memberId = $request->member_id;
        $dto->monthId = $request->month_id;
        $dto->amount = $request->amount;
        $dto->description = $request->description;
        $dto->paymentMethodId = $request->payment_method_id;

        try{
            $this->depositService->createDeposit($dto);
            return redirect()->back()->with('success', 'Deposit made successfully');
        }catch (Throwable $exception){
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }



}
