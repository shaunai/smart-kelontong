<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel stores
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('message');
            $table->enum('category', ['reminder_debt', 'stock_alert', 'payment_receipt']);
            $table->string('status', 50)->default('pending');
            
            // message_id untuk menyimpan ID log pengiriman dari layanan SMTP (seperti Gmail)
            $table->string('message_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};