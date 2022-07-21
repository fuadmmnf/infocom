<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMessage extends Model
{
    protected $guarded = [];
    protected $casts = [
        'customers' => 'array'
    ];
}
