<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopAddress extends Model {
    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
