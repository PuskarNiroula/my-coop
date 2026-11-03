<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 * @method static orderBy(string $string)
 * @method static where(string $string, string $value)
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


    public function getId(){
        return $this->expense_head_id;
    }

}
