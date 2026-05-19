<?php

use Illuminate\Database\Migrations\Migration;

// Payment method enum already updated in create_sales_table migration.
// This file is kept for compatibility only.
return new class extends Migration
{
    public function up(): void {}
    public function down(): void {}
};
