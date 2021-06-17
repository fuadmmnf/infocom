<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPlan extends Model
{
    protected $guarded = [];

    public function complains()
    {
        return $this->hasMany(Complain::class, 'slaplan_id');
    }
}
