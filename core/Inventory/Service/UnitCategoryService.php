<?php

namespace Inventory\Service;

use App\Models\UnitCategory;
use Inventory\Dto\UnitCategoryCreateDto;
use Inventory\Dto\UnitCategoryUpdateDto;
use Inventory\Enum\UnitCategoryStatusEnum;
use Inventory\Exception\DuplicateUnitCategoryNameException;
use Inventory\Exception\IsFixedException;
use Inventory\Exception\UnitCategoryDoesNotExistException;

class UnitCategoryService
{
    /**
     * @throws DuplicateUnitCategoryNameException
     */
    public function create(UnitCategoryCreateDto $dto): void{
        $this->ensureCategoryNameIsUnique($dto->name);
        UnitCategory::create([
            'name' => $dto->name,
            'base_unit_id' => $dto->baseUnitId,
            'status'=>UnitCategoryStatusEnum::ACTIVE->value,
        ]);

    }

    /**
     * @throws DuplicateUnitCategoryNameException
     * @throws IsFixedException
     * @throws UnitCategoryDoesNotExistException
     *
     */
    public function update( UnitCategoryUpdateDto $dto): void
    {
        $this->ensureCategoryNameIsUnique($dto->name, $dto->id);
        $category=$this->validateIfFixed($dto->id);
        $category->update([
            'name' => $dto->name,
            'base_unit_id' => $dto->baseUnitId,
        ]);
    }

    /**
     * @throws IsFixedException
     * @throws UnitCategoryDoesNotExistException
     */
    public function activate(int $id): void{
        $category=$this->validateIfFixed($id);
        $category->update([
            'status'=>UnitCategoryStatusEnum::ACTIVE->value,
        ]);
    }

    /**
     * @throws IsFixedException
     * @throws UnitCategoryDoesNotExistException
     */
    public function deactivate(int $id): void{
        $category=$this->validateIfFixed($id);
        $category->update([
            'status'=>UnitCategoryStatusEnum::INACTIVE->value,
        ]);
    }

    /**
     * @throws DuplicateUnitCategoryNameException
     */
    private function ensureCategoryNameIsUnique(string $name, ?int $id = null): void
    {
        $exists = UnitCategory::query()
            ->where('name', $name)
            ->when($id !== null, fn ($query) => $query->where('unit_category_id', '!=', $id))
            ->exists();

        if ($exists)
            throw new DuplicateUnitCategoryNameException($name);

    }

    /**
     * @throws IsFixedException
     * @throws UnitCategoryDoesNotExistException
     */
    private function validateIfFixed(int $id): UnitCategory{
        $category = $this->getUnitCategoryById($id);
        if($category->is_fixed) throw new IsFixedException();
        return $category;
    }

    /**
     * @throws UnitCategoryDoesNotExistException
     */
    private function getUnitCategoryById(int $id): UnitCategory{
        $category = UnitCategory::find($id);
        if(!$category) throw new UnitCategoryDoesNotExistException($id);
        return $category;
    }

}
