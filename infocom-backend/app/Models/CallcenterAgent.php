<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallcenterAgent extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    use SoftDeletes;
    public function user(){
        return $this->belongsTo(User::class);
    }
}
