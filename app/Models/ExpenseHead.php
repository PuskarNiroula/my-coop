<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 * @property mixed $name
 * @property mixed $status
 */
class ExpenseHead extends Model
{
    protected $table = 'expense_heads';
    protected $primaryKey = "expense_head_id";

    public $timestamps=false;

    protected $fillable = [
        'name',
        'status',
    ];


}
