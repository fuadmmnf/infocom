<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpTopic extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function slaplan(){
        return $this->hasOne(SlaPlan::class);
    }
}
