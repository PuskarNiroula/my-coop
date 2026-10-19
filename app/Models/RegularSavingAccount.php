<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
