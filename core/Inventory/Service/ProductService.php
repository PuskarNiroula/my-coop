<?php

namespace Inventory\Service;

use App\Models\Inventory\Product;
use Inventory\Dto\ProductCreateDto;
use Inventory\Dto\ProductUpdateDto;
use Inventory\Enum\ProductStatusEnum;
use Inventory\Exception\DuplicateNameException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductService
{

    /**
     * @throws DuplicateNameException
     * @throws Throwable
     */
    public function createProduct(ProductCreateDto $dto): void
    {
        $this->validateName($dto->name);

        $imagePath = null;

        try {
            DB::transaction(function () use ($dto, &$imagePath) {
                $product = Product::create([
                    'name' => $dto->name,
                    'description' => $dto->description,
                    'product_type' => $dto->type,
                    'base_unit_id' => $dto->baseUnit,
                    'image'=> $dto->image,
                    'status' => ProductStatusEnum::ACTIVE->value,
                ]);

                if ($dto->image) {
                    $imagePath = $dto->image->store('products', 'public');

                    $product->update([
                        'image' => $imagePath,
                    ]);
                }
            });
        } catch (Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    /**
     * @throws DuplicateNameException
     * @throws Throwable
     */
    public function updateProduct(ProductUpdateDto $dto): void
    {
        $this->validateName($dto->name, $dto->productId);

        $imagePath = null;
        $oldImagePath = null;

        try {
            DB::transaction(function () use ($dto, &$imagePath, &$oldImagePath) {
                $product = Product::findOrFail($dto->productId);

                $data = [
                    'name' => $dto->name,
                    'description' => $dto->description,
                    'base_unit_id' => $dto->baseUnit,
                ];

                if ($dto->image) {
                    $imagePath = $dto->image->store('products', 'public');
                    $oldImagePath = $product->image;

                    $data['image'] = $imagePath;
                }

                $product->update($data);
            });

            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

        } catch (Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function activate(int $id): void{
        $product = Product::find($id);
        if(!$product) throw new \Exception("Product not found");
        $product->update([
            'status' => ProductStatusEnum::ACTIVE->value,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deactivate(int $id): void{
        $product = Product::find($id);
        if(!$product) throw new \Exception("Product not found");
        $product->update([
            'status' => ProductStatusEnum::INACTIVE->value,
        ]);
    }

    /**
     * @throws DuplicateNameException
     */
    private function validateName(string $name, $id=null): void
    {
        $query=Product::where('name',$name);
        if($id!=null) $query->where('product_id','!=',$id);
        if($query->exists()) throw  new DuplicateNameException("product name  $name already exists");
    }

}
