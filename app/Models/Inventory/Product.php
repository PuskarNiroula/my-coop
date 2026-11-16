<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static create(array $array)
 * @method static where(string $string, string $name)
 * @method static find(int $id)
 */
class Product extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    public function getId():?int{
        return $this->product_id;
    }
    public function type():BelongsTo{
        return $this->belongsTo(UnitCategory::class,'product_type','unit_category_id');
    }
    public function baseUnit():BelongsTo{
        return $this->belongsTo(Unit::class,'base_unit_id','unit_id');
    }
}
