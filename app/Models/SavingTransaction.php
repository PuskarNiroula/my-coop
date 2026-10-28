<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 * @method static where(string $string, string $value)
 */
class SavingTransaction extends Model
{
    protected $table = "savings_transactions";
    protected $primaryKey = "id";

    public $guarded=[];
    public function account():hasOne{
        return $this->hasOne(MemberAccount::class,"member_account_id","account_id");
    }

    public function dayBook():belongsTo{
        return $this->belongsTo(DayBook::class,"day_book_id","day_book_id");
    }
    public function year():BelongsTo{
        return $this->belongsTo(Year::class,"year_id","year_id");
    }
    public function fiscalYear():BelongsTo{
        return $this->belongsTo(FiscalYear::class,"fiscal_year_id","fiscal_year_id");
    }
    public function month():BelongsTo{
        return $this->belongsTo(Month::class,"month_id","month_id");
    }


}
