<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static find(int $id)
 * @method static findOrFail(int $id)
 */
class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $guarded = [];
    protected $primaryKey = 'supplier_id';

    public $timestamps = false;


    public function getId():?int{
        return $this->supplier_id;
    }
}
