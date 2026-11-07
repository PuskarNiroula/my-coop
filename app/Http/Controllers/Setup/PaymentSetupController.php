<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use PaymentMethod\Dto\PaymentMethodCreateDto;
use PaymentMethod\Dto\PaymentMethodUpdateDto;
use PaymentMethod\Exception\PaymentMethodNotFoundException;
use PaymentMethod\Service\PaymentMethodService;

class PaymentSetupController extends Controller
{
    public function __construct(
        private readonly PaymentMethodService $paymentMethodService
    ){}
    public function index(){
        $paymentMethods = PaymentMethod::all();
        return view('payment-setup.index',compact('paymentMethods'));
    }
    public function create(){
        return view('payment-setup.create');
    }
    public function store(Request $request){
       try{
           $request->validate([
               'name'=>'required|unique:payment_methods',
               'initial_balance'=>'required|numeric|min:0',
           ]);
           $dto = new PaymentMethodCreateDto();
           $dto->name = $request->name;
           $dto->initialBalance = $request->initial_balance;

           $this->paymentMethodService->create($dto);
           return redirect()->route('payment-setup.index')->with('success','Payment Method Created Successfully');
       }catch (Exception $exception){
           return redirect()->back()->with('error',$exception->getMessage())->withInput();
       }

    }
    public function edit(int $id){

       try{
           $paymentMethod = PaymentMethod::find($id);
           if(!$paymentMethod) throw new PaymentMethodNotFoundException($id);
           return view('payment-setup.edit',compact('paymentMethod'));
       }catch (Exception $exception){
           return redirect()->back()->with('error',$exception->getMessage())->withInput();
       }
    }
    public function update(Request $request,int $id){
        try{
            $request->validate([
                'name'=>'required'
            ]);
            $dto = new PaymentMethodUpdateDto();
            $dto->name = $request->name;
            $dto->id = $id;
            $this->paymentMethodService->update($dto);
            return redirect()->route('payment-setup.index')->with('success','Payment Method Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function activate(int $id){
        try{
            $this->paymentMethodService->activate($id);
            return redirect()->route('payment-setup.index')->with('success','Payment Method Activated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function deactivate(int $id){
        try{
            $this->paymentMethodService->deactivate($id);
            return redirect()->route('payment-setup.index')->with('success','Payment Method Activated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }



}
