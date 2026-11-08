<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
