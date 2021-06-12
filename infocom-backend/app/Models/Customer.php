<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function complains(){
        return $this->hasMany(Complain::class);
    }
}
