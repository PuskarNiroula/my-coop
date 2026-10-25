<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, int $memberId)
 */
class RegularSavingAccount extends Model
{

    protected $table = "regular_savings";
    protected $primaryKey = "regular_saving_id";


    protected $fillable = [
        "member_id",
        "balance",
        "status",
    ];


    public function member():BelongsTo{
        return $this->belongsTo(Member::class,"member_id","member_id");
    }

}
