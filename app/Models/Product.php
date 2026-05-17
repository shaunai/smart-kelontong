<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id', 'parent_id', 'sku', 'category', 'name',
        'unit', 'conversion_qty', 'min_stock',
    ];

    public function store() { return $this->belongsTo(Store::class); }

    // Relasi ke produk eceran (Unit terkecil)
    public function parent() { return $this->belongsTo(Product::class, 'parent_id'); }

    // Relasi ke varian (Misal: Dus/Pak dari produk ini)
    public function variants() { return $this->hasMany(Product::class, 'parent_id'); }

    public function batches() { return $this->hasMany(ProductBatch::class); }
    public function saleDetails() { return $this->hasMany(SaleDetail::class); }
}
