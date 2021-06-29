<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    protected $casts = [
        'services' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complains()
    {
        return $this->hasMany(Complain::class);
    }

    public function popaddress()
    {
        return $this->belongsTo(PopAddress::class);
    }
}
