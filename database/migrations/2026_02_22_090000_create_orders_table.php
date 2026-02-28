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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // THIS IS CRITICAL - creates auto-incrementing ID
            $table->string('user_id')->nullable(); // Make nullable if not always present
            $table->string('customer_name')->nullable(); // Add this for guest checkout
            $table->string('product_name');
            $table->integer('quantity')->default(1);
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // Use string instead of enum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};