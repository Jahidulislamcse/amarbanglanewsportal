<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('transaction_id')->unique();
            $table->string('product_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->string('phone_number')->nullable();
             $table->text('address')->nullable();
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'cancelled'
            ])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->string('payment_type')->default('product_purchase');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_payments');
    }
};
