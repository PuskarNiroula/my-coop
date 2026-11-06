<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentSetupController extends Controller
{
    public function index(){
        $paymentMethods = PaymentMethod::all();
        return view('payment-setup.index',compact('paymentMethods'));
    }
    public function create(){
        return view('payment-setup.create');
    }
    public function store(){
        //we will make it later
    }
    public function edit(){
        return view('payment-setup.edit');
    }
    public function update(){
        //later
    }
    public function activate(){
        //later
    }
    public function deactivate(){
        //later
    }



}
