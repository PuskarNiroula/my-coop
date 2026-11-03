<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, true $true)
 * @method static get(string $string, string $string1)
 * @method static findOrFail(mixed $monthId)
 * @method static orderBy(string $string)
 */
class Month extends Model
{
    protected $table = 'months';
    protected $guarded = [];
    protected $primaryKey = 'month_id';

    public function getId():?int{
        return $this->month_id;
    }

}
