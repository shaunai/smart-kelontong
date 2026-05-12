<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['store_id', 'name', 'phone', 'address'];

    public function store() { return $this->belongsTo(Store::class); }
    public function debts() { return $this->hasMany(Debt::class); }
}
