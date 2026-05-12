<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = ['store_id', 'type', 'amount', 'category', 'description', 'reference_id'];

    public function store() { return $this->belongsTo(Store::class); }
}
