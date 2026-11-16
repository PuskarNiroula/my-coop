<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'products';
    protected $primaryKey = 'product_id';
}
