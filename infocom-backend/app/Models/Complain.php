<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complain extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $casts= [
        'complain_time' => 'datetime',
        'assigned_time' => 'datetime',
        'finished_time' => 'datetime',
        'approved_time' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function slaplan()
    {
        return $this->belongsTo(SlaPlan::class);
    }

    public function helptopic()
    {
        return $this->belongsTo(HelpTopic::class);
    }

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
