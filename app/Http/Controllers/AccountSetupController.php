<?php

namespace App\Http\Controllers;

use AccountTypes\Dto\AccountTypeCreateDto;
use AccountTypes\Dto\AccountTypeUpdateDto;
use AccountTypes\Enum\InterestFrequencyEnum;
use App\Models\AccountType;
use Exception;
use Illuminate\Http\Request;
use AccountTypes\Service\AccountTypeService;
use Illuminate\Validation\Rule;

class AccountSetupController extends Controller
{
    public function __construct(
        private readonly AccountTypeService $accountTypeService
    ){}

    public function index(Request $request)
    {
        $accountTypes = AccountType::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('account_type_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('account-type.index', compact('accountTypes'));
    }
    public function create(){
        $interestFrequencyArray = InterestFrequencyEnum::getAsArray();
        return view('account-type.create',compact('interestFrequencyArray'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:account_types,name',
            'interest_calculation_frequency' => 'required|in:monthly,semi_annually,annually,maturity',
            'interest_rate' => 'required|numeric|between:0,100',
        ]);

        $dto = new AccountTypeCreateDto();
        $dto->name = $request->name;
        $dto->interestCalculationFrequency = $request->interest_calculation_frequency;
        $dto->interestRate = $request->interest_rate;

        try{
            $this->accountTypeService->create($dto);
            return redirect()->route('account-types.index')->with('success','Account Type Created Successfully');

        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }



    }

    public function activate(int $accountTypeId){
        try{
            $this->accountTypeService->activate($accountTypeId);
            return redirect()->route('account-types.index')->with('success','Account Type Activated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }
    public function deactivate(int $accountTypeId){
        try{
            $this->accountTypeService->deactivate($accountTypeId);
            return redirect()->route('account-types.index')->with('success','Account Type Deactivated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }
    public function edit(int $accountTypeId){
        $interestFrequencyArray = InterestFrequencyEnum::getAsArray();
        $accountType = AccountType::find($accountTypeId);
        if(!$accountType){
            return redirect()->route('account-types.index')->with('error','Account Type Not Found');
        }
        return view('account-type.edit', compact(['accountType', 'interestFrequencyArray']));
    }
    public function update(Request $request, int $accountTypeId){
        $request->validate([
            'name' => 'required|string|max:255|unique:account_types,name,' . $accountTypeId . ',account_type_id',
            'interest_calculation_frequency' => ['required', Rule::in(InterestFrequencyEnum::getAsOneDArray())],
            'interest_rate' => 'required|numeric|between:0,100',
        ]);

        $dto = new AccountTypeUpdateDto();
        $dto->name = $request->name;
        $dto->interestCalculationFrequency = $request->interest_calculation_frequency;
        $dto->interestRate = $request->interest_rate;
        $dto->accountTypeId = $accountTypeId;

        try{
            $this->accountTypeService->update($dto);
            return redirect()->route('account-types.index')->with('success','Account Type Updated Successfully');

        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

}
