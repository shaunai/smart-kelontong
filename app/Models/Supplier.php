<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['store_id', 'name', 'phone', 'address'];

    public function store() { return $this->belongsTo(Store::class); }
    public function batches() { return $this->hasMany(ProductBatch::class); }
}
