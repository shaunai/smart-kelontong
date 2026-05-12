<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('sku', 100)->nullable();
            $table->string('name');
            $table->string('unit', 50)->default('pcs');
            $table->integer('conversion_qty')->default(1);
            $table->integer('min_stock')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};