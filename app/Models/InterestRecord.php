<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class InterestRecord extends Model
{
    protected $table = 'interest_records';
    protected $primaryKey = 'interest_record_id';
    protected $guarded = [];

    public function startMonth():BelongsTo{
        return $this->belongsTo(Month::class, 'start_month_id', 'month_id','months');
    }
    public function endMonth():BelongsTo{
        return $this->belongsTo(Month::class, 'end_month_id', 'month_id','months');
    }
    public function startYear():BelongsTo{
        return $this->belongsTo(Year::class, 'start_year_id', 'year_id','years');
    }
    public function endYear():BelongsTo{
        return $this->belongsTo(Year::class, 'end_year_id', 'year_id','years');
    }
    public function fiscalYear():BelongsTo{
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id', 'fiscal_year_id','fiscal_years');
    }


}
