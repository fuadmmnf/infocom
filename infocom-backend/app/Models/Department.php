<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function supportagents(){
        return $this->hasMany(SupportAgent::class);
    }
}
