<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'months';
    protected $guarded = [];
    protected $primaryKey = 'month_id';
}
