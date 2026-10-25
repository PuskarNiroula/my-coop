<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @property mixed $date_english
 * @property mixed $date_nepali
 * @property mixed $status
 */
class DayBook extends Model
{
    protected $primaryKey = 'day_book_id';

    protected $fillable = [
        'date_english',
        'date_nepali',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_english' => 'date',
        ];
    }
}
