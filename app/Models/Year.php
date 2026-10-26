<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $yearId)
 */
class Year extends Model
{
    protected $table = 'years';
    protected $fillable = ['name','status'];
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey= "year_id";

    public function getId(){
        return $this->year_id;
    }
}
