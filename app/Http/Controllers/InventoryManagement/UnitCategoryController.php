<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\UnitCategory;

class UnitCategoryController extends Controller
{
    public function index(){
        $categories=UnitCategory::with('unit')->get();
        return view('inventory.unit-category.index',compact('categories'));
    }

}
