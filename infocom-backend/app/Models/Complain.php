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

    public function activityLog(){
        return ($this->department_id != '' ? [] : [
            'department' => $this->department->name
        ]) + [
            'time' => $this->time->format('Y-m-d H:i'),
            'member' => `{$this->editor->user->name} ({$this->editor->user->phone})`,
            'task' => $this->type,
            'complain' => `TT{$this->id}`,
            'customer' => `{$this->customer->user->name} ({$this->customer->user->phone})`
        ];
    }
}
