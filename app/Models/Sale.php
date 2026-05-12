<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'store_id', 'user_id', 'invoice_number', 'total_price', 
        'payment_method', 'payment_status', 'midtrans_snap_token', 'midtrans_transaction_id'
    ];

    public function store() { return $this->belongsTo(Store::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function details() { return $this->hasMany(SaleDetail::class); }
    public function debt() { return $this->hasOne(Debt::class); }
}
