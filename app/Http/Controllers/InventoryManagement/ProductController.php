<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Inventory\Unit;
use App\Models\Inventory\UnitCategory;
use Illuminate\Http\Request;
use Inventory\Dto\ProductCreateDto;
use Inventory\Dto\ProductUpdateDto;
use Inventory\Enum\UnitCategoryStatusEnum;
use Inventory\Service\ProductService;
use Exception;
use Throwable;
class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ){}
    public function index(){
        $products = Product::with('baseUnit')->with('type')->get();
        return view('inventory.product.index',compact('products'));
    }
    public function create()
    {
        $productTypes = UnitCategory::where('status',UnitCategoryStatusEnum::ACTIVE->name)->pluck('name','unit_category_id');
        $baseUnits = Unit::where('status',UnitCategoryStatusEnum::ACTIVE->name)->pluck('name','unit_id');;

        return view('inventory.product.create', compact('productTypes', 'baseUnits'));
    }
    public function store(Request $request)
    {
       try{
           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'product_type' => 'required|integer|exists:unit_categories,unit_category_id',
               'base_unit' => 'required|integer|exists:units,unit_id',
               'description' => 'nullable|string|max:1000',
               'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
           ]);

           $dto = new ProductCreateDto();

           $dto->fromArray([
               'name' => $validated['name'],
               'product_type_id' => $validated['product_type'],
               'base_unit_id' => $validated['base_unit'],
               'description' => $validated['description'] ?? null,
               'image' => $request->file('image'),
           ]);

           $this->productService->createProduct($dto);

           return redirect()
               ->route('products.index')
               ->with('success', 'Product created successfully.');
       }catch (Exception $exception){
           return redirect()->back()->withErrors([$exception->getMessage()])->withInput();
       }
    }
    public function edit($id){
        $product = Product::findOrFail($id);
        return view('inventory.product.edit',compact('product'));
    }

    /**
     * @throws Throwable
     */
    public function update(int $id, Request $request){

       try{
           $validated = $request->validate([
               'name' => 'required|string|max:255',
               'base_unit' => 'required|integer|exists:units,unit_id',
               'description' => 'nullable|string|max:1000',
               'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
           ]);

           $dto = new ProductUpdateDto();
           $dto->name = $validated['name'];
           $dto->baseUnit = $validated['base_unit'];
           $dto->description = $validated['description'] ?? null;
           $dto->productId = $id;
           $dto->image = $request->file('image');
           $this->productService->updateProduct($dto);

           return redirect()->route('products.index')->with('success','Product updated successfully.');
       }  catch (Exception $exception){
           return redirect()->back()->withErrors([$exception->getMessage()])->withInput();
       }
    }
    public function activate(int $id){
        try{
            $this->productService->activate($id);
            return redirect()->route('products.index')->with('success','Product activated successfully.');
        }catch (Exception $exception){
            return redirect()->route('products.index')->withErrors([$exception->getMessage()]);
        }
    }
    public function deactivate(int $id){
        try{
            $this->productService->deactivate($id);
            return redirect()->route('products.index')->with('success','Product activated successfully.');
        }catch (Exception $exception){
            return redirect()->route('products.index')->withErrors([$exception->getMessage()]);
        }
    }



}
