<?php

namespace Inventory\Dto;

use Illuminate\Http\UploadedFile;

class ProductUpdateDto extends ProductCreateDto
{
   public int $productId;
}

