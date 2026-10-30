<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static upsert(array $statements, string[] $array, string[] $array1)
 */
class SavingMonthlyStatement extends Model
{
    protected $table = 'saving_statements';
    protected $primaryKey = "statement_id";

    protected $guarded = [];

    public function memberAccount():BelongsTo{
        return $this->belongsTo(MemberAccount::class,'member_account_id','member_account_id','member_accounts');
    }
    public function month():BelongsTo{
        return $this->belongsTo(Month::class,'month_id','month_id','months');
    }
    public function year():BelongsTo{
        return $this->belongsTo(Year::class,'year_id','year_id','years');

    }
    public function fiscalYear():BelongsTo{
        return $this->belongsTo(FiscalYear::class,'fiscal_year_id','fiscal_year_id','fiscal_years');
    }
}
