<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Inventory\Dto\UnitCategoryCreateDto;
use Inventory\Dto\UnitCategoryUpdateDto;
use Inventory\Service\UnitCategoryService;
use Exception;

class UnitCategoryController extends Controller
{
    public function __construct(
       private readonly UnitCategoryService $unitCategoryService
    ){}
    public function index(){
        $categories=UnitCategory::with('unit')->get();
        return view('inventory.unit-category.index',compact('categories'));
    }
    public function create(){
        $units = Unit::where('status','active')->get();
        return view('inventory.unit-category.create',compact('units'));
    }
    public function store(Request $request){
        try{
            $request->validate([
                'name'=>'required|string',
                'base_unit_id'=>'nullable|integer',
            ]);
            $dto = new UnitCategoryCreateDto();
            $dto->name = $request->name;
            $dto->baseUnitId = $request->base_unit_id;
            $this->unitCategoryService->create($dto);
            return redirect()->route('inventory.category.index')->with('success','Unit Category created successfully');
        }catch (Exception $exception){
            return redirect()->route('inventory.category.index')->with('error',$exception->getMessage())->withInput();
        }

    }

    public function edit(int $id){
        $category = UnitCategory::where('unit_category_id',$id)->with('unit')->first();

        if(!$category) return redirect()->route('inventory.category.index')->with('error','Unit Category not found');
        if($category->is_fixed) return redirect()->route('inventory.category.index')->with('error','Unit Category already fixed');
        $units = Unit::where('status','active')->get();
        return view('inventory.unit-category.edit',compact(['category','units']));
    }
    public function update(int $id,Request $request){
        try{
            $request->validate([
                'name'=>'required|string',
                'base_unit_id'=>'nullable|integer',
            ]);
            $dto = new UnitCategoryUpdateDto();
            $dto->name = $request->name;
            $dto->id = $id;
            $dto->baseUnitId = $request->base_unit_id;
            $this->unitCategoryService->update($dto);
            return redirect()->route('inventory.category.index')->with('success','Unit Category updated successfully');
        }catch (Exception $exception){
            return redirect()->route('inventory.category.index')->with('error',$exception->getMessage())->withInput();
        }

    }
    public function activate(int $id){
        try{
            $this->unitCategoryService->activate($id);
        }
        catch (Exception $exception){
            return redirect()->route('inventory.category.index')->with('error',$exception->getMessage())->withInput();
        }


    }
    public function deactivate(int $id){
        try{
            $this->unitCategoryService->deactivate($id);
        }
        catch (Exception $exception){
            return redirect()->route('inventory.category.index')->with('error',$exception->getMessage())->withInput();
        }

    }

}
