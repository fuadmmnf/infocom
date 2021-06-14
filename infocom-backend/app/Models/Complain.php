<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complain extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(CallcenterAgent::class);
    }

    public function editor()
    {
        return $this->belongsTo(SupportAgent::class);
    }
}
