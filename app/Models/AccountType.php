<?php

namespace App\Models;

use AccountTypes\Enum\AccountTypeStatusEnum;
use Illuminate\Database\Eloquent\Model;


/**
 * @method static where(string $string, string $name)
 * @method static create(array $array)
 * @method static find(int $id)
 */
class AccountType extends Model
{
    protected $table = "account_types";
    protected $primaryKey = "account_type_id";
    public $timestamps = false;

    protected $fillable = ['name','interest_rate','interest_payout_frequency','status'];

    public function activate():void{
        $this->status = AccountTypeStatusEnum::ACTIVE->value;
        $this->save();
    }
    public function deactivate():void{
        $this->status = AccountTypeStatusEnum::INACTIVE->value;
        $this->save();
    }
    public function getId(){
        return $this->account_type_id;
    }



}
