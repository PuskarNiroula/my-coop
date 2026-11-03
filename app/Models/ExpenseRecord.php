<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static find(int $id)
 * @method static orderBy(string $string)
 * @method static whereNotNull(string $string)
 */
class ExpenseRecord extends Model
{
    protected $table = 'expense_records';
    protected $primaryKey = "expense_record_id";
    protected $guarded = [];


    public function getId(){
        return $this->expense_record_id;
    }

    public function expenseHead():BelongsTo{
        return $this->belongsTo(ExpenseHead::class,'expense_head_id','expense_head_id');
    }
}
