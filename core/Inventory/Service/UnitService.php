<?php

namespace Inventory\Service;

use App\Models\Unit;
use Exception;
use Inventory\Dto\UnitCreateDto;
use Inventory\Dto\UnitUpdateDto;
use Inventory\Enum\UnitStatusEnum;
use Inventory\Exception\DuplicateUnitNameException;


class UnitService
{
    /**
     * @throws DuplicateUnitNameException
     */
    public function create(UnitCreateDto $dto): void
    {
        $this->ensureUnitNameIsUnique($dto->name);
        Unit::create([
            "name" => $dto->name,
            "symbol"=> $dto->symbol,
            'conversion_rate'=>$dto->conversationRate,
            'unit_category_id' => $dto->categoryId,
        ]);

    }

    /**
     * @throws DuplicateUnitNameException
     * @throws Exception
     */
    public function update(UnitUpdateDto $dto): void{

        $this->ensureUnitNameIsUnique($dto->name);
        $unit=Unit::find($dto->unitId);
        if(!$unit)
            throw new Exception("Unit not found");
        if($unit->is_fixed) throw new Exception("Unit already fixed");

        $unit->update([
            "name" => $dto->name,
            "symbol"=> $dto->symbol,
            'conversion_rate'=>$dto->conversationRate,
            'unit_category_id' => $dto->categoryId,
        ]);
        $unit->save();

    }

    /**
     * @throws Exception
     */
    public function activate(int $id):void{
        $unit=Unit::find($id);
        if(!$unit) throw new Exception("Unit not found");

        $unit->status=UnitStatusEnum::ACTIVE->value;
        $unit->save();
    }

    /**
     * @throws Exception
     */
    public function deactivate(int $id):void{
        $unit=Unit::find($id);

        if(!$unit) throw new Exception("Unit not found");

        if($unit->is_fixed) throw new Exception("Unit already fixed");
        $unit->status=UnitStatusEnum::INACTIVE->value;
        $unit->save();
    }

    /**
     * @throws DuplicateUnitNameException
     */
    private function ensureUnitNameIsUnique(string $name, ?int $id = null): void{
        $exists = Unit::query()
            ->where('name', $name)
            ->when($id !== null, fn ($query) => $query->where('unit_id', '!=', $id))
            ->exists();
        if($exists) throw new DuplicateUnitNameException($name);

    }

}
