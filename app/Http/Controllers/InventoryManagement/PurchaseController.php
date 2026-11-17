<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryTransaction;
use App\Models\Inventory\Product;
use App\Models\Inventory\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(){
        $purchases=InventoryTransaction::where('transaction_type','purchase')
            ->orderBy('transaction_date','desc')->paginate(10);
        return view('inventory.purchase.index',compact('purchases'));
    }
    public function create(){
        $suppliers=Supplier::where('status','active')->get();
        $products=Product::where('status','active')->get();
        return view('inventory.purchase.create',compact(['suppliers','products']));
    }
    public function edit(int $id){
        //todo
    }
    public function store(Request $request){
        //todo
    }
    public function update(Request $request,int $id){
        //todo
    }
    public function activate(int $id){
        //todo
    }
    public function deactivate(int $id){
        //todo
    }

}
