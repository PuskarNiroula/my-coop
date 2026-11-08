<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCategory extends Model
{
    protected $table = 'unit_categories';
    protected $primaryKey = 'unit_category_id';
    public $timestamps = false;
    protected $guarded = [];

    public function getId(){
        return $this->unit_category_id;
    }
}
