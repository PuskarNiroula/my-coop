<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AccountType extends Model
{
    protected $table = "account_types";
    protected $primaryKey = "account_type_id";
    public $timestamps = false;

    protected $fillable = ['account_type_name','interest_rate','interest_payout_frequency','status'];

}
