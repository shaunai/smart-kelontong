<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    protected $fillable = ['store_id', 'recipient', 'message', 'category', 'status', 'fonte_id'];

    public function store() { return $this->belongsTo(Store::class); }
}
