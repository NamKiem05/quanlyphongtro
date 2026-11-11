<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseSetting extends Model
{
    protected $fillable = [
        'house_name',
        'address',
        'phone',
        'description',
    ];
}