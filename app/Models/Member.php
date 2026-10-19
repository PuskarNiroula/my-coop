<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'father_name',
        'mother_name',
        'birth_date_eng',
        'gender',
        'address',
        'image',
        'citizenship_number',
        'citizenship_front',
        'citizenship_back',
        'legal_aid_relation',
        'legal_aid_name',
        'legal_aid_citizenship_number',
        "birth_date_nepali",
        "profile_image"
    ];
    protected $table = 'members';
    protected $primaryKey = 'member_id';

}
