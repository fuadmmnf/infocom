<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlaPlan extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function complains()
    {
        return $this->hasMany(Complain::class, 'slaplan_id');
    }

    public function helptopic(){
        return $this->belongsTo(HelpTopic::class);
    }
}
