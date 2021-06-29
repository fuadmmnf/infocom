<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model {
    use SoftDeletes;
    protected $guarded = [];

    public function supportagents() {
        return $this->hasMany(SupportAgent::class);
    }

    public function leader() {
        return $this->belongsTo(SupportAgent::class, 'leader_id');
    }
}
