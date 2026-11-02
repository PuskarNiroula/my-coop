<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class InterestDistribution extends Model
{
    protected $table = 'interest_distribution';
    protected $guarded = [];

    public function memberAccount():BelongsTo{
        return $this->belongsTo(MemberAccount::class,'account_id','member_account_id');
    }



}
