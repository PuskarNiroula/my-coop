<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $yearId)
 * @method static where(string $string, true $true)
 * @property mixed $year_id
 * @property mixed $sequence
 */
class Year extends Model
{
    protected $table = 'years';
    protected $fillable = ['name', 'status', 'sequence',
        'is_current'
    ];
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey= "year_id";

    public function getId(){
        return $this->year_id;
    }
}
