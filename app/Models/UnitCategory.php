<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @method static find(int $id)
 * @method static where(string $string, int $id)
 */
class UnitCategory extends Model
{
    protected $table = 'unit_categories';
    protected $primaryKey = 'unit_category_id';
    public $timestamps = false;
    protected $guarded = [];

    public function getId(){
        return $this->unit_category_id;
    }
    public function unit():HasOne{
        return $this->hasOne(Unit::class,'unit_id','base_unit_id');
    }
}
