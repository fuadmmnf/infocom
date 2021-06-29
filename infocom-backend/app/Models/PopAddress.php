<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PopAddress extends Model {
    use SoftDeletes;
    protected $guarded = [];
    public function customers(){
        return $this->hasMany(Customer::class, 'popaddress_id');
    }
}
