<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $memberId)
 * @method static where(string $string, int $memberId)
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
