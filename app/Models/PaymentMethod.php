<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, int $id)
 * @method static find(int $id)
 */
class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'payment_method_id';
    protected $guarded = ['payment_method_id'];
    public $timestamps = false;

    public function getId():?int{
        return $this->payment_method_id;
    }



}
