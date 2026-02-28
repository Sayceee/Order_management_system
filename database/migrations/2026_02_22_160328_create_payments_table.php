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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Fix: Make sure it references the correct table and column
            $table->foreignId('order_id')
                  ->constrained('orders') // Explicitly reference orders table
                  ->onDelete('cascade');
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable(); // Remove unique for now
            $table->string('mpesa_receipt')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('phone');
            $table->string('status')->default('pending');
            $table->integer('result_code')->nullable();
            $table->text('result_desc')->nullable();
            $table->json('raw_callback')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};