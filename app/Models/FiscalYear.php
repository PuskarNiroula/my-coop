<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static findOrFail(int $id)
 * @method static where(string $string, true $true)
 * @property mixed $fiscal_year_id
 */
class FiscalYear extends Model
{
    protected $table = 'fiscal_years';
    protected $fillable = ['name','status','is_closed','is_current'];
    protected $primaryKey="fiscal_year_id";

    public function getId(){
        return $this->fiscal_year_id;
    }
}
