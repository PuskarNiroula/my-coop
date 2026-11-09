<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Inventory\Dto\UnitCreateDto;
use Inventory\Dto\UnitUpdateDto;
use Inventory\Service\UnitService;
use Exception;

class UnitController extends Controller
{
    public function __construct(
        private readonly UnitService $unitService,
    ){}
    public function index()
    {
        $units = Unit::with('category')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('symbol', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->latest('unit_id')
            ->paginate(10)
            ->withQueryString();

        return view('inventory.unit.index', compact('units'));
    }
    public function create(){
        $categories = UnitCategory::where('status','active')->get();
        return view('inventory.unit.create',compact('categories'));
    }
    public function store(Request $request){
        try{
            $request->validate([
                'name' => 'required|string',
                'symbol' => 'required|string',
                'category_id' => 'required|integer',
                'conversion_rate' => 'required|numeric:1,99999',
            ]);
            $dto = new UnitCreateDto();
            $dto->name = $request->name;
            $dto->symbol = $request->symbol;
            $dto->categoryId = $request->category_id;
            $dto->conversationRate = $request->conversion_rate;

            $this->unitService->create($dto);
            return redirect()->route('inventory.units.index')->with('success', 'Unit Created Successfully');


        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['error' => $exception->getMessage()])->withInput();
        }

    }

    public function edit(int $id){
        $unit = Unit::with('category')->findOrFail($id);
        if($unit->is_fixed) return redirect()->route('inventory.units.index')->with('error','Unit already fixed');
        $categories = UnitCategory::where('status','active')->get();
        return view('inventory.unit.edit', compact('unit','categories'));
    }
    public function update(int $id,Request $request){
        try{
            $request->validate([
                'name' => 'required|string',
                'symbol' => 'required|string',
                'category_id' => 'required|integer',
                'conversion_rate' => 'required|numeric:1,99999',
            ]);
            $dto = new UnitUpdateDto();
            $dto->unitId = $id;
            $dto->name = $request->name;
            $dto->symbol = $request->symbol;
            $dto->categoryId = $request->category_id;
            $dto->conversationRate = $request->conversion_rate;

            $this->unitService->update($dto);
            return redirect()->route('inventory.units.index')->with('success', 'Unit Updated Successfully');


        }catch (Exception $exception){
            return redirect()->back()->withErrors(['error' => $exception->getMessage()])->withInput();
        }

    }

}
