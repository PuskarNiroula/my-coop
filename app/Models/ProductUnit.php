<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUnit extends Model
{
    protected $table = 'product_units';
    protected $guarded = [];
    protected $primaryKey = 'product_unit_id';

    public function product():BelongsTo{
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    public function unit():BelongsTo{
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
