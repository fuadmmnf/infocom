<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpTopic extends Model
{
    protected $guarded = [];

    public function slaplan(){
        return $this->hasOne(SlaPlan::class);
    }
}
