<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_number', 100)->unique();
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['cash', 'qris', 'transfer'])->default('cash');
            $table->enum('payment_status', ['paid', 'debt', 'pending'])->default('paid');
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};