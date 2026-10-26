<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class MemberAccount extends Model
{
    protected $table = 'member_accounts';
    protected $primaryKey = 'member_account_id';

    protected $guarded = [];

    public function member(): BelongsTo{
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
