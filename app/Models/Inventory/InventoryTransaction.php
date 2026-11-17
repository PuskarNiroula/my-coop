<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate(int $int)
 * @method static where(string $string, string $string1)
 */
class InventoryTransaction extends Model
{
    protected $table='inventory_transactions';
    protected $guarded=[];
    protected $primaryKey='transaction_id';

}
