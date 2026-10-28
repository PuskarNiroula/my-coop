<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancelTransaction extends Model
{
    protected $table = 'transaction_cancels';

    protected $primaryKey = 'transaction_cancel_id';

    protected $fillable = [
        'reason',
        'cancellation_date',
        'cancellation_date_nepali',
        'cancelled_by',
        'saving_transaction_id',
    ];

    public function savingTransaction(): BelongsTo
    {
        return $this->belongsTo(
            SavingTransaction::class,
            'saving_transaction_id',
            'saving_transaction_id'
        );
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'cancelled_by',
            'user_id'
        );
    }
}
