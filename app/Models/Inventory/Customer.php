<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Customer extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'address',
        'phone',
    ];
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    public function getId(){
        return $this->customer_id;
    }

}
