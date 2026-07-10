<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
    'logo_path',
    'name',
    'address',
    'city',
    'province',
    'phone',
    'director_name',
    'bank_name',
    'bank_account_number',
    'bank_account_name',
];
    
}
