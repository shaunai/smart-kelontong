<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $fillable = [
        'store_id', 'sale_id', 'customer_id', 'amount', 
        'remaining_balance', 'due_date', 'status'
    ];

    public function store() { return $this->belongsTo(Store::class); }
    public function sale() { return $this->belongsTo(Sale::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
