<?php

namespace Inventory\Dto;

use Illuminate\Http\UploadedFile;

class ProductCreateDto
{
    public string $name;
    public ?string $description = null;
    public ?UploadedFile $image = null;
    public ?int $type;
    public int $baseUnit;

    public function fromArray(array $data): self
    {
        $this->name = $data['name'];
        $this->description = $data['description'] ?? null;
        $this->image = $data['image'] ?? null;
        $this->type = (int) $data['product_type_id'];
        $this->baseUnit = (int) $data['base_unit_id'];

        return $this;
    }
}

