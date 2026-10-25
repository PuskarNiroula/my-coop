<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiscalYear extends Model
{
    protected $table = 'fiscal_years';
    protected $fillable = ['name','status'];

    public function getId(){
        return $this->fiscal_year_id;
    }
}
