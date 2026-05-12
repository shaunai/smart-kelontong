<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'footer_note'];

    public function users() { return $this->hasMany(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function suppliers() { return $this->hasMany(Supplier::class); }
    public function customers() { return $this->hasMany(Customer::class); }
    public function sales() { return $this->hasMany(Sale::class); }
    public function debts() { return $this->hasMany(Debt::class); }
    public function cashFlows() { return $this->hasMany(CashFlow::class); }
}
