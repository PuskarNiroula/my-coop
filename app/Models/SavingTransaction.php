<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 */
class SavingTransaction extends Model
{
    protected $table = "savings_transactions";
    protected $primaryKey = "id";

    public $guarded=[];
    public function savingAccount():hasOne{
        return $this->hasOne(RegularSavingAccount::class,"regular_saving_id","regular_saving_id");
    }

    public function dayBook():belongsTo{
        return $this->belongsTo(DayBook::class,"day_book_id","day_book_id");
    }


}
