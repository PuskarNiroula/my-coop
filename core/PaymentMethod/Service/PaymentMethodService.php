<?php

namespace PaymentMethod\Service;
use App\Models\PaymentMethod;
use PaymentMethod\Dto\PaymentMethodCreateDto;
use PaymentMethod\Dto\PaymentMethodUpdateDto;
use Exception;
use PaymentMethod\Enum\PaymentMethodStatusEnum;

class PaymentMethodService
{

    /**
     * @throws Exception
     */
    public function create(PaymentMethodCreateDto $paymentMethodCreateDto):void{
        $this->getDuplicatePaymentMethod($paymentMethodCreateDto->name);
        PaymentMethod::create([
            'name' => $paymentMethodCreateDto->name,
            'balance'=>$paymentMethodCreateDto->initialBalance,
            'status'=>PaymentMethodStatusEnum::ACTIVE->value,
        ]);


    }

    /**
     * @throws Exception
     */
    public function update(PaymentMethodUpdateDto $paymentMethodUpdateDto):void{
        $this->getDuplicatePaymentMethod($paymentMethodUpdateDto->name,$paymentMethodUpdateDto->id);
        PaymentMethod::where('payment_method_id', $paymentMethodUpdateDto->id)
        ->update([
            'name' => $paymentMethodUpdateDto->name,
        ]);
    }

    /**
     * @throws Exception
     */
    public function activate(int $id):void{
        $pm = PaymentMethod::find($id);
        if(!$pm)
            throw new Exception("Payment method not found");
        $pm->status = PaymentMethodStatusEnum::ACTIVE->value;
        $pm->save();

    }

    /**
     * @throws Exception
     */
    public function deactivate(int $id):void{
        $pm = PaymentMethod::find($id);
        if(!$pm)
            throw new Exception("Payment method not found");
        $pm->status = PaymentMethodStatusEnum::INACTIVE->value;
        $pm->save();

    }

    /**
     * @throws Exception
     */
    private function getDuplicatePaymentMethod(string $name,?int $id=null):void{
        $query=PaymentMethod::query();

        if(!is_null($id)) $query->where('payment_method_id','!=',$id);


        if($query->where('name', $name)->exists()){
            throw new Exception('Payment Method already exists');
        }


    }
}
