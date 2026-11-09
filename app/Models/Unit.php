<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, string $string1)
 * @method static create(array $array)
 * @method static find(int $unitId)
 */
class Unit extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'unit_id';
    protected $table='units';

    protected $guarded = [];

    public function getId(){
        return $this->unit_id;
    }

    public function category():BelongsTo{
        return $this->belongsTo(UnitCategory::class,'unit_category_id','unit_category_id');
    }


}
